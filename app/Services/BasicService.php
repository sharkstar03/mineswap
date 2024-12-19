<?php

namespace App\Services;

use App\Http\Traits\Notify;
use App\Models\Investment;
use App\Models\ManageTime;
use App\Models\Transaction;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Image;

class BasicService
{
    use Notify;

    public function validateImage(object $getImage, string $path)
    {
        if ($getImage->getClientOriginalExtension() == 'jpg' or $getImage->getClientOriginalName() == 'jpeg' or $getImage->getClientOriginalName() == 'png') {
            $image = uniqid() . '.' . $getImage->getClientOriginalExtension();
        } else {
            $image = uniqid() . '.jpg';
        }
        Image::make($getImage->getRealPath())->resize(300, 250)->save($path . $image);
        return $image;
    }

    public function validateDate(string $date)
    {
        if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateKeyword(string $search, string $keyword)
    {
        return preg_match('~' . preg_quote($search, '~') . '~i', $keyword);
    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {

        $varb = $wallet . "?amount=" . $amount;
        return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
    }

    public function preparePaymentUpgradation($order)
    {
        $basic = (object)config('basic');
        $gateway = $order->gateway;


        if ($order->status == 0) {
            $order['status'] = 1;
            $order->update();

            $user = $order->user;

            $balance_type = config('basic.currency');

            if ($order->investment_id) {
                $investment  = $order->planInvestment;
                $investment->status =  1;
                $investment->update();

                $this->getWallet($investment);

                $remarks = optional($investment->plan)->name . ' has been purchased';
                $this->makeTransaction($user, $investment->price, getAmount($order->charge), '-',  $balance_type, $order->transaction, $remarks);


                $this->sendMailSms($user, 'PLAN_PURCHASE', [
                    'order_id' => $order->transaction,
                    'amount' => getAmount($investment->price),
                    'currency' => $basic->currency_symbol,
                    'charge' => getAmount($order->charge),
                    'plan_name' => optional($investment->plan)->name ,
                ]);

                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($investment->price),
                    'currency' => $basic->currency_symbol,
                    'plan_name' => optional($investment->plan)->name ,
                ];
                $action = [
                    "link" => route('admin.user.plan-purchaseLog', $user->id),
                    "icon" => "fa fa-money-bill-alt "
                ];
                $this->adminPushNotification('PLAN_PURCHASE', $msg, $action);


            }else{
                $user->balance += $order->amount;
                $user->save();

                $this->makeTransaction($user, getAmount($order->amount), getAmount($order->charge),  '+', $balance_type, $order->transaction, 'Deposit Via ' . $gateway->name);

                if ($basic->deposit_commission == 1) {
                    $this->setMultiLevelBonus($user, getAmount($order->amount), $type = 'deposit');
                }

                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('PAYMENT_COMPLETE', $msg, $action);
                $this->sendMailSms($user, 'PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'charge' => getAmount($order->charge),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'remaining_balance' => getAmount($user->balance)
                ]);

            }


            session()->forget('orderId');
        }
    }




    public function setMultiLevelBonus($user, $amount, $commissionType = ''){

        $basic = (object) config('basic');
        if($basic->deposit_commission == 0){
            return false;
        }

        $userId = $user->id;
        $i = 1;
        $level = \App\Models\Referral::where('commission_type', $commissionType)->count();
        while ($userId != "" || $userId != "0" || $i < $level) {
            $me = \App\Models\User::with('referral')->find($userId);
            $refer = $me->referral;
            if (!$refer) {
                break;
            }
            $commission = \App\Models\Referral::where('commission_type', $commissionType)->where('level', $i)->first();
            if (!$commission) {
                break;
            }
            $com = ($amount * $commission->percent) / 100;
            $new_bal = getAmount($refer->balance + $com);
            $refer->balance = $new_bal;
            $refer->save();

            $trx = strRandom();
            $balance_type =  $basic->currency;

            $remarks = ' level ' . $i . ' Referral bonus From ' . $user->username;

            $this->makeTransaction($refer, $com, 0, '+', $balance_type, $trx, $remarks);


            $bonus = new \App\Models\ReferralBonus();
            $bonus->from_user_id = $refer->id;
            $bonus->to_user_id = $user->id;
            $bonus->level = $i;
            $bonus->amount = getAmount($com);
            $bonus->main_balance = $new_bal;
            $bonus->transaction = $trx;
            $bonus->type = $commissionType;
            $bonus->remarks = $remarks;
            $bonus->save();


            $this->sendMailSms($refer,  'REFERRAL_BONUS', [
                'transaction_id' => $trx,
                'amount' => getAmount($com),
                'currency' => $basic->currency_symbol,
                'bonus_from' => $user->username,
                'final_balance' => $refer->balance,
                'level' => $i
            ]);


            $msg = [
                'bonus_from' => $user->username,
                'amount' => getAmount($com),
                'currency' => $basic->currency_symbol,
                'level' => $i
            ];
            $action = [
                "link" => route('user.referral.bonus'),
                "icon" => "fa fa-money-bill-alt"
            ];
            $this->userPushNotification($refer,'REFERRAL_BONUS', $msg, $action);

            $userId = $refer->id;
            $i++;
        }
        return 0;
    }

    public function setBonus($user, $plan, $commissionType = '')
    {
        $basic = (object) config('basic');
        if($basic->plan_sale_commission == 0){
            return false;
        }

        if(!$user->referral_id){
            return false;
        }

        $refer = \App\Models\User::find($user->referral_id);
        if (!$refer) {
            return false;
        }

        if(0 < $plan->referral){
            $amount = $plan->price;
            $commission = ($amount * $plan->referral) / 100;
            $refer->balance += $commission;
            $refer->save();


            $trx = strRandom();
            $balance_type = config('basic.currency');
            $remarks = 'Referral bonus From ' . $user->username. ' For '.$plan->name;

            $bonus = new \App\Models\ReferralBonus();
            $bonus->from_user_id = $refer->id;
            $bonus->to_user_id = $user->id;
            $bonus->amount = getAmount($commission);
            $bonus->main_balance = $refer->balance;
            $bonus->transaction = $trx;
            $bonus->type = $commissionType;
            $bonus->remarks = $remarks;
            $bonus->save();
            $this->makeTransaction($refer, $commission, 0, '+',  $balance_type, $trx, $remarks);

            $this->sendMailSms($refer, 'PURCHASE_PLAN_REFERRAL_BONUS', [
                'transaction_id' => $trx,
                'amount' => getAmount($commission),
                'currency' => $basic->currency_symbol,
                'bonus_from' => $user->username,
                'final_balance' => $refer->balance,
                'plan' => $plan->name
            ]);


            $msg = [
                'bonus_from' => $user->username,
                'amount' => getAmount($commission),
                'currency' => $basic->currency_symbol,
                'plan' => $plan->name
            ];
            $action = [
                "link" => route('user.referral.bonus'),
                "icon" => "fa fa-money-bill-alt"
            ];
            $this->userPushNotification($refer, 'PURCHASE_PLAN_REFERRAL_BONUS', $msg, $action);
        }
        return 0;
    }



    public function setInvestment($plan,$user, $status = 1)
    {
        $transaction = strRandom();
        $info['Name'] = $plan->name;
        $info['Mining'] = $plan->miner->name;
        $info['Hashrate'] = $plan->hash_rate_speed . ' '.$plan->hash_rate_unit;
        $info['Duration'] = $plan->duration.' '.$plan->periodText;

        $investment['plan_info'] = $info;
        $investment['user_id'] = $user->id;
        $investment['plan_id'] = $plan->id;
        $investment['code'] = optional($plan->miner)->code;
        $investment['price'] = $plan->price;
        $investment['profitable_cycle'] = $plan->duration*$plan->period;
        $investment['remaining_cycle'] = $plan->duration*$plan->period;
        $investment['transaction'] = $transaction;
        $investment['minimum_profit'] = $plan->minimum_profit;
        $investment['maximum_profit'] = $plan->maximum_profit??$plan->minimum_profit;;
        $investment['status'] = $status;

        $invest = Investment::create($investment);
        if($invest->status == 1){
            $this->getWallet($invest);
            $this->setBonus($user, $plan, $type = 'plan_sell');
        }

        return $invest;
    }


    /**
     * @param $user
     * @param $amount
     * @param $charge
     * @param $trx_type
     * @param $balance_type
     * @param $trx_id
     * @param $remarks
     */
    public function makeTransaction($user, $amount, $charge, $trx_type = null, $balance_type, $trx_id, $remarks = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = getAmount($amount);
        $transaction->charge = $charge;
        $transaction->trx_type = $trx_type;
        $transaction->balance_type = $balance_type;
        $transaction->final_balance = $user->balance;
        $transaction->trx_id = $trx_id;
        $transaction->remarks = $remarks;
        $transaction->save();
    }

    /**
     * @param $invest
     */
    public function getWallet($invest): void
    {
         UserWallet::firstOrCreate([
            'user_id' => $invest->user_id,
            'code' => strtoupper($invest->code)
        ]);
    }


}
