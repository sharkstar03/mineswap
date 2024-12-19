<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\KYC;
use App\Models\Language;
use App\Models\PayoutLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\UserWallet;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\BasicService;

class UsersController extends Controller
{
    use Upload, Notify;

    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.users.list', compact('users'));
    }

    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $users = User::when(isset($search['search']), function ($query) use ($search) {
            return $query->where('email', 'LIKE', "%{$search['search']}%")
                ->orWhere('username', 'LIKE', "%{$search['search']}%");
        })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->paginate(config('basic.paginate'));
        return view('admin.users.list', compact('users', 'search'));
    }


    public function activeMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            User::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'User Status Has Been Active');
            return response()->json(['success' => 1]);
        }
    }

    public function inactiveMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            User::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'User Status Has Been Deactive');
            return response()->json(['success' => 1]);
        }
    }


    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        $wallets = $user->wallets()->get();
        $languages = Language::all();
        return view('admin.users.edit-user', compact('user', 'languages', 'wallets'));
    }

    public function userUpdate(Request $request, $id)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });
        $userData = Purify::clean($request->except('_token', '_method'));
        $user = User::findOrFail($id);
        $rules = [
            'firstname' => 'sometimes|required',
            'lastname' => 'sometimes|required',
            'username' => 'sometimes|required|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First Name is required',
            'lastname.required' => 'Last Name is required',
        ];

        $Validator = Validator::make($userData, $rules, $message);
        if ($Validator->fails()) {
            return back()->withErrors($Validator)->withInput();
        }

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?? null;
                $user->image = $this->uploadImage($request->image, config('location.user.path'), config('location.user.size'), $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }
        $user->firstname = $userData['firstname'];
        $user->lastname = $userData['lastname'];
        $user->username = $userData['username'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->language_id = $userData['language_id'];
        $user->address = $userData['address'];
        $user->status = ($userData['status'] == 'on') ? 0 : 1;
        $user->email_verification = ($userData['email_verification'] == 'on') ? 0 : 1;
        $user->sms_verification = ($userData['sms_verification'] == 'on') ? 0 : 1;
        $user->two_fa_verify = ($userData['two_fa_verify'] == 'on') ? 1 : 0;
        $user->save();


        $this->sendMailSms($user, 'PROFILE_UPDATE', [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        return back()->with('success', 'Updated Successfully.');
    }

    public function loginAccount(Request $request, $id)
    {
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('user.home');
    }


    public function passwordUpdate(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:5|same:password_confirmation',
        ]);
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        $this->sendMailSms($user, 'PASSWORD_CHANGED', [
            'password' => $request->password
        ]);
        return back()->with('success', 'Updated Successfully.');
    }


    public function userBalanceUpdate(Request $request, $id)
    {
        $userData = Purify::clean($request->all());
        if ($userData['balance'] == null) {
            return back()->with('error', 'Balance Value Empty!');
        }
        $control = (object)config('basic');
        $user = User::findOrFail($id);


        $trxId = strRandom();

        if ($userData['add_status'] == "1") {
            $user->balance += $userData['balance'];
            $user->save();
            BasicService::makeTransaction($user, $userData['balance'], 0, '+', config('basic.currency'), $trxId, 'Added Balance');
            $msg = [
                'amount' => getAmount($userData['balance']),
                'currency' => $control->currency,
                'main_balance' => $user->balance,
                'transaction' => $trxId
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $this->userPushNotification($user, 'ADD_BALANCE', $msg, $action);


            $this->sendMailSms($user, 'ADD_BALANCE', [
                'amount' => getAmount($userData['balance']),
                'currency' => $control->currency,
                'main_balance' => $user->balance,
                'transaction' => $trxId
            ]);

            return back()->with('success', 'Balance Add Successfully.');
        } else {

            if ($userData['balance'] > $user->balance) {
                return back()->with('error', 'Insufficient Balance to deducted.');
            }
            $user->balance -= $userData['balance'];
            $user->save();


            BasicService::makeTransaction($user, $userData['balance'], 0, '-', config('basic.currency'), $trxId, 'Deducted Balance');

            $msg = [
                'amount' => getAmount($userData['balance']),
                'currency' => $control->currency,
                'main_balance' => $user->balance,
                'transaction' => $trxId
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $this->userPushNotification($user, 'DEDUCTED_BALANCE', $msg, $action);

            $this->sendMailSms($user, 'DEDUCTED_BALANCE', [
                'amount' => getAmount($userData['balance']),
                'currency' => $control->currency,
                'main_balance' => $user->balance,
                'transaction' => $trxId
            ]);
            return back()->with('success', 'Balance deducted Successfully.');
        }


    }


    public function emailToUsers()
    {
        return view('admin.users.mail-form');
    }

    public function sendEmailToUsers(Request $request)
    {
        $req = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'subject' => 'sometimes|required',
            'message' => 'sometimes|required'
        ];
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $allUsers = User::where('status', 1)->get();
        foreach ($allUsers as $user) {
            $this->mail($user, null, [], $req['subject'], $req['message']);
        }
        return back()->with('success', 'Mail Send Successfully');
    }


    public function sendEmail($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.single-mail-form', compact('user'));
    }

    public function sendMailUser(Request $request, $id)
    {
        $req = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'subject' => 'sometimes|required',
            'message' => 'sometimes|required'
        ];
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::findOrFail($id);
        $this->mail($user, null, [], $req['subject'], $req['message']);

        return back()->with('success', 'Mail Send Successfully');
    }


    public function investments($id)
    {
        $user = User::findOrFail($id);
        $userid = $user->id;
        $transaction = $user->investments()->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        $title = $user->username . " : Investment";
        return view('admin.users.investment', compact('user', 'userid', 'transaction', 'title'));
    }

    public function transaction($id)
    {
        $user = User::findOrFail($id);
        $userid = $user->id;
        $transaction = $user->transaction()->paginate(config('basic.paginate'));
        return view('admin.users.transactions', compact('user', 'userid', 'transaction'));
    }

    public function funds($id)
    {
        $user = User::findOrFail($id);
        $userid = $user->id;
        $funds = $user->funds()->paginate(config('basic.paginate'));
        return view('admin.users.fund-log', compact('user', 'userid', 'funds'));
    }

    public function payoutLog($id)
    {
        $user = User::findOrFail($id);
        $userid = $user->id;
        $records = PayoutLog::whereUser_id($user->id)->whereNotIn('status', [0])->orderBy('id', 'DESC')->with('user')->paginate(config('basic.paginate'));
        return view('admin.users.payout-log', compact('user', 'userid', 'records'));
    }

    public function commissionLog($id)
    {
        $user = User::findOrFail($id);
        $userid = $user->id;
        $transactions = $user->referralBonusLog()->latest()->with('user', 'bonusBy:id,firstname,lastname')->paginate(config('basic.paginate'));
        return view('admin.users.commissionLog', compact('user', 'userid', 'transactions'));
    }

    public function wallet($id)
    {
        $user = User::findOrFail($id);
        $userid = $user->id;
        $title = "Wallet Settings";
        $wallets = $user->wallets()->get();
        return view('admin.users.wallet', compact('user', 'userid', 'title', 'wallets'));
    }

    public function walletUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $wallets = UserWallet::where('user_id', $user->id)->get();
        $walletCoins = $wallets->map(function ($item) {
            return $item['code'];
        })->toArray();
        $req = Purify::clean($request->all($walletCoins));

        $rules = [];
        $message = [];
        foreach ($req as $key => $cus) {
            $rules[$key] = [];
            array_push($rules[$key], 'max:90');
            $message[$key . ".max"] = "The " . strtoupper($key) . " may not be greater than 90 characters.";
        }
        $this->validate($request, $rules, $message);


        $wallets->map(function ($item) use ($req, $request) {
            $item->wallet_address = (strlen($req[$item->code]) == 0) ? null : $req[$item->code];
            $item->update();
        });


        return back()->with('success', 'Updated Successfully.');
    }

    public function referralMember($id)
    {
        $user = User::findOrFail($id);
        $referrals = getLevelUser($user->id);

        return view('admin.users.referral', compact('user', 'referrals'));
    }


    public function loggedIn()
    {
        $logs = UserLogin::orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.users.logged_in', compact('logs'));
    }

    public function singleLoggedIn($id)
    {
        $logs = UserLogin::where('user_id', $id)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.users.logged_in', compact('logs'));
    }

    public function kycPendingList()
    {
        $title = "Pending KYC";
        $logs = KYC::orderBy('id', 'DESC')->where('status', 0)->with(['user', 'admin'])->paginate(config('basic.paginate'));
        return view('admin.users.kycList', compact('logs', 'title'));
    }

    public function kycList()
    {
        $title = "KYC Log";
        $logs = KYC::orderBy('id', 'DESC')->where('status', '!=', 0)->paginate(config('basic.paginate'));
        return view('admin.users.kycList', compact('logs', 'title'));
    }

    public function userKycHistory(User $user)
    {
        $title = $user->username . " : KYC Log";
        $logs = KYC::orderBy('id', 'DESC')->where('user_id', $user->id)->paginate(config('basic.paginate'));
        return view('admin.users.kycList', compact('logs', 'title'));
    }


    public function kycAction(Request $request, $id)
    {

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required', Rule::in(['1', '2'])],
        ]);
        $data = KYC::where('id', $request->id)->whereIn('status', [0])->with('user')->firstOrFail();
        $basic = (object)config('basic');

        if ($request->status == '1') {
            $data->status = 1;
            $data->admin_id = auth()->guard('admin')->id();
            $data->update();
            $user = $data->user;
            if ($data->kyc_type == 'address-verification') {
                $user->address_verify = 2;
            } else {
                $user->identity_verify = 2;
            }
            $user->save();

            $this->sendMailSms($user, 'KYC_APPROVED', [
                'kyc_type' => kebab2Title($data->kyc_type)
            ]);


            $msg = [
                'kyc_type' => kebab2Title($data->kyc_type)
            ];
            $action = [
                "link" => '#',
                "icon" => "fas fa-file-alt text-white"
            ];
            $this->userPushNotification($user, 'KYC_APPROVED', $msg, $action);

            session()->flash('success', 'Approve Successfully');
            return back();

        } elseif ($request->status == '2') {
            $data->status = 2;
            $data->admin_id = auth()->guard('admin')->id();
            $data->update();

            $user = $data->user;
            if ($data->kyc_type == 'address-verification') {
                $user->address_verify = 3;
            } else {
                $user->identity_verify = 3;
            }
            $user->save();


            $this->sendMailSms($user, 'KYC_REJECTED', [
                'kyc_type' => kebab2Title($data->kyc_type)
            ]);
            $msg = [
                'kyc_type' => kebab2Title($data->kyc_type)
            ];
            $action = [
                "link" => '#',
                "icon" => "fas fa-file-alt text-white"
            ];
            $this->userPushNotification($user, 'KYC_REJECTED', $msg, $action);

            session()->flash('success', 'Reject Successfully');
            return back();
        }
    }


}
