<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Models\PayoutLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Facades\App\Services\BasicService;
class PayoutRecordController extends Controller
{
    use Notify;
    public function index()
    {
        $page_title = "Payout Logs";
        $records = PayoutLog::whereNotIn('status', [0,1])->orderBy('id', 'DESC')->with('user')->paginate(config('basic.paginate'));
        return view('admin.payout.logs', compact('records', 'page_title'));
    }

    public function request()
    {
        $page_title = "Payout Request";
        $records = PayoutLog::where('status', 1)->orderBy('id', 'DESC')->with('user', 'method')->paginate(config('basic.paginate'));
        return view('admin.payout.logs', compact('records', 'page_title'));
    }
    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $records = PayoutLog::when(isset($search['name']), function ($query) use ($search) {

                return $query->where('trx_id', 'LIKE', $search['name'])
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'LIKE', "%{$search['name']}%")
                            ->orWhere('username', 'LIKE', "%{$search['name']}%");
                    });

            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->where('status', '!=', 0)
            ->with('user', 'method')
            ->paginate(config('basic.paginate'));
        $records->appends($search);

        $page_title = "Search Payout Logs";
        return view('admin.payout.logs', compact('records', 'page_title'));
    }

    public  function action(Request $request, $id){

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required',Rule::in(['2','3'])],
        ]);

        $data = PayoutLog::where('id', $request->id)->whereIn('status',[1])->with('user','wallet')->firstOrFail();


        $userWallet = $data->wallet;

        $user = $data->user;


        $basic = (object) config('basic');

        if($request->status == '2'){
            $data->status = 2;
            $data->feedback = $request->feedback;
            $data->save();

            $this->sendMailSms($user, 'PAYOUT_APPROVE', [
                'method' => optional($userWallet->miner)->name,
                'amount' => $data->amount,
                'charge' => $data->charge,
                'currency' => $data->code,
                'transaction' => $data->trx_id,
                'feedback' => $data->feedback
            ]);


            $msg = [
                'amount' => $data->amount,
                'currency' => $data->code,
                'feedback' => $data->feedback
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt "
            ];
            $this->userPushNotification($user,'PAYOUT_APPROVE', $msg, $action);

            session()->flash('success','Approve Successfully');


        }elseif ($request->status == '3'){

            $data->status = 3;
            $data->feedback = $request->feedback;
            $data->save();


            $userWallet->balance += $data->net_amount;
            $userWallet->save();


            $remarks = $data->amount . ' ' . $data->code . ' withdraw amount has been refunded';
            BasicService::makeTransaction($user, $data->amount, $data->charge, '+', $data->code, $data->trx_id, $remarks);


            $this->sendMailSms($user, 'PAYOUT_REJECTED', [
                'method' => optional($data->method)->name,
                'amount' => $data->amount,
                'charge' => $data->charge,
                'currency' => $data->code,
                'transaction' => $data->trx_id,
                'feedback' => $data->feedback
            ]);


            $msg = [
                'amount' => $data->amount,
                'currency' => $data->code,
                'feedback' => $data->feedback
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt "
            ];
            $this->userPushNotification($user,'PAYOUT_REJECTED', $msg, $action);

            session()->flash('success','Reject Successfully');
        }
        return back();
    }
}
