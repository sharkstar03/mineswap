<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Models\Fund;
use App\Models\MiningPlan;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;

class PaymentLogController extends Controller
{
    use Notify;
    public function index()
    {
        $page_title = "Payment Logs";
        $funds = Fund::where('status', '!=', 0)->where('investment_id', null)->orderBy('id', 'DESC')->with('user', 'gateway')->paginate(config('basic.paginate'));
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }

    public function pending()
    {
        $page_title = "Payment Pending";
        $funds = Fund::where('status', 2)->where('gateway_id', '>', 999)->orderBy('id', 'DESC')->with('user', 'gateway')->paginate(config('basic.paginate'));
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }

    public function search(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $funds = Fund::when(isset($search['name']), function ($query) use ($search) {

            return $query->where('transaction', 'LIKE', $search['name'])
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['name']}%");
                });
        })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when($search['status'] != -1, function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->where('status', '!=', 0)
            ->where('investment_id', null)
            ->with('user', 'gateway')
            ->paginate(config('basic.paginate'));
        $funds->appends($search);
        $page_title = "Search Payment Logs";
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }

    public function action(Request $request, $id)
    {

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required', Rule::in(['1', '3'])],
        ]);
        $data = Fund::where('id', $request->id)->whereIn('status', [2])->with('user', 'gateway', 'planInvestment')->firstOrFail();
        $basic = (object)config('basic');

        $gateway = $data->gateway;

        $req = Purify::clean($request->all());
        $req = (object)$req;


        if ($request->status == '1') {
            $data->status = 1;
            $data->feedback = $request->feedback;
            $data->update();

            $user = $data->user;
            $balance_type = config('basic.currency');


            if ($data->investment_id) {
                $investment  = $data->planInvestment;
                $investment->status =  1;
                $investment->update();

                BasicService::getWallet($investment);

                $plan = MiningPlan::where('id', $investment->plan_id)->with('miner')->first();
                if($plan){
                    BasicService::setBonus($user, $plan, $type = 'plan_sell');
                }


                $balance_type = config('basic.currency');
                $remarks = optional($investment->plan)->name . ' has been purchased';
                BasicService::makeTransaction($user, $investment->price, getAmount($data->charge), '-',  $balance_type, $data->transaction, $remarks);

                $this->sendMailSms($user, 'PLAN_PURCHASE', [
                    'order_id' => $data->transaction,
                    'amount' => getAmount($investment->price),
                    'currency' => $basic->currency_symbol,
                    'charge' => getAmount($data->charge),
                    'plan_name' => optional($investment->plan)->name,
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
                $user->balance += $data->amount;
                $user->save();

                $remarks = getAmount($data->amount) . ' ' . $basic->currency . ' payment amount has been approved';

                BasicService::makeTransaction($user, getAmount($data->amount), getAmount($data->charge),  '+', $balance_type, $data->transaction, $remarks);

                if ($basic->deposit_commission == 1) {
                    BasicService::setMultiLevelBonus($user, getAmount($data->amount), $type = 'deposit');
                }




                $msg = [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'charge' => getAmount($data->charge),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'remaining_balance' => getAmount($user->balance),
                    'feedback' => $data->feedback
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fas fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user, 'PAYMENT_APPROVED', $msg, $action);

                $this->sendMailSms($user, 'PAYMENT_APPROVED', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'charge' => getAmount($data->charge),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'remaining_balance' => getAmount($user->balance),
                    'feedback' => $data->feedback
                ]);
            }





            session()->flash('success', 'Approve Successfully');
            return back();

        } elseif ($request->status == '3') {

            $data->status = 3;
            $data->feedback = $request->feedback;
            $data->update();
            $user = $data->user;

            $this->sendMailSms($user, 'PAYMENT_REJECTED', [
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'gateway_name' => optional($data->gateway)->name,
                'transaction' => $data->transaction,
                'feedback' => $data->feedback
            ]);

            $msg = [
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'feedback' => $data->feedback,
            ];
            $action = [
                "link" => route('admin.user.fundLog', $user->id),
                "icon" => "fas fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'PAYMENT_REJECTED', $msg, $action);

            session()->flash('success', 'Reject Successfully');
            return back();
        }
    }
}
