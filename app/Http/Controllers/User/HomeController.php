<?php

namespace App\Http\Controllers\User;

use App\Helper\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Fund;
use App\Models\Gateway;
use App\Models\IdentifyForm;
use App\Models\Investment;
use App\Models\KYC;
use App\Models\Language;
use App\Models\MiningPlan;
use App\Models\PayoutLog;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

use hisorange\BrowserDetect\Parser as Browser;

class HomeController extends Controller
{
    use Upload, Notify;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['walletBalance'] = getAmount($this->user->balance);
        $data['wallets'] = $this->user->wallets()->toBase()->get(['code', 'balance']);
        $data['referralMember'] = User::where('referral_id',$this->user->id)->count('id');
        $data['referralBonus'] = ReferralBonus::where('from_user_id',$this->user->id)->sum('amount');

        $monthlyBonus = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        ReferralBonus::where('from_user_id',$this->user->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyBonus) {
                $monthlyBonus->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['bonus'] = $monthlyBonus;

        $monthlyInvest = collect(['January' => 0, 'February' => 0, 'March' => 0, 'April' => 0, 'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0, 'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0]);
        Investment::where('user_id',$this->user->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->select(
                DB::raw('sum(price) as totalAmount'),
                DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get()->map(function ($item) use ($monthlyInvest) {
                $monthlyInvest->put($item['months'], round($item['totalAmount'], 2));
            });
        $monthly['invest'] = $monthlyInvest;



        return view($this->theme . 'user.dashboard', $data, compact('monthly'));
    }


    public function transaction()
    {
        $transactions = $this->user->transaction()->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.index', compact('transactions'));
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $transaction = Transaction::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(@$search['remark'], function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transactions = $transaction->appends($search);


        return view($this->theme . 'user.transaction.index', compact('transactions'));

    }

    public function fundHistory()
    {
        $funds = Fund::where('user_id', $this->user->id)->where('status', '!=', 0)->orderBy('id', 'DESC')->with('gateway')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.fundHistory', compact('funds'));
    }

    public function fundHistorySearch(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $funds = Fund::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', $search['name']);
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->with('gateway')
            ->paginate(config('basic.paginate'));
        $funds->appends($search);
        return view($this->theme . 'user.transaction.fundHistory', compact('funds'));
    }


    public function addFund()
    {
        if (session()->get('orderId') != null) {
            return redirect(route('user.payment'));
        }

        $data['totalPayment'] = null;
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        return view($this->theme . 'user.addFund', $data);
    }

    public function payment()
    {
        $orderId = session()->get('orderId');
        if ($orderId == null) {
            return redirect(route('user.addFund'));
        }

        $investment = Investment::where('transaction', $orderId)->where('user_id', $this->user->id)->where('status', 0)->orderBy('id', 'desc')->firstOrFail();

        $data['totalPayment'] = $investment->price;
        $data['investment'] = $investment;
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        return view($this->theme . 'user.payment', $data);
    }


    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), []);

        $data['user'] = $this->user;
        $data['languages'] = Language::all();
        $data['identityFormList'] = IdentifyForm::where('status', 1)->get();
        if ($request->has('identity_type')) {
            $validator->errors()->add('identity', '1');
            $data['identity_type'] = $request->identity_type;
            $data['identityForm'] = IdentifyForm::where('slug', trim($request->identity_type))->where('status', 1)->firstOrFail();
            return view($this->theme . 'user.profile.myprofile', $data)->withErrors($validator);
        }

        return view($this->theme . 'user.profile.myprofile', $data);
    }


    public function updateProfile(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $image = $request->image;
        $this->validate($request, [
            'image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    if (($image->getSize() / 1000000) > 4) {
                        throw ValidationException::withMessages(['image' => 'Images MAX  4MB ALLOW!']);
                    }
                    if (!in_array($ext, $allowedExtensions)) {
                        throw ValidationException::withMessages(['image' => 'Only png, jpg, jpeg images are allowed']);
                    }
                }
            ]
        ]);

        $user = $this->user;
        if ($request->hasFile('image')) {
            $path = config('location.user.path');
            try {
                $user->image = $this->uploadImage($image, $path);
            } catch (\Exception $exp) {
                return back()->with('error', 'Could not upload your ' . $image)->withInput();
            }
        }
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }


    public function updateInformation(Request $request)
    {

        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $req = Purify::clean($request->all());
        $user = $this->user;
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'address' => 'required',
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First Name field is required',
            'lastname.required' => 'Last Name field is required',
        ];

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user->language_id = $req['language_id'];
        $user->firstname = $req['firstname'];
        $user->lastname = $req['lastname'];
        $user->username = $req['username'];
        $user->address = $req['address'];
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }


    public function updatePassword(Request $request)
    {

        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('password', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function verificationSubmit(Request $request)
    {
        $identityFormList = IdentifyForm::where('status', 1)->get();
        $rules['identity_type'] = ["required", Rule::in($identityFormList->pluck('slug')->toArray())];
        $identity_type = $request->identity_type;
        $identityForm = IdentifyForm::where('slug', trim($identity_type))->where('status', 1)->firstOrFail();

        $params = $identityForm->services_form;

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                    array_push($verifyImages, $key);
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('identity', '1');

            return back()->withErrors($validator)->withInput();
        }


        $path = config('location.kyc.path').date('Y').'/'.date('m').'/'.date('d');
        $collection = collect($request);

        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $this->uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    session()->flash('error', 'Could not upload your ' . $inKey);
                                    return back()->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
        }

        try {

            DB::beginTransaction();

            $user = $this->user;
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = $identityForm->slug;
            $kyc->details = $reqField;
            $kyc->save();

            $user->identity_verify =  1;
            $user->save();

            if(!$kyc){
                DB::rollBack();
                $validator->errors()->add('identity', '1');
                return back()->withErrors($validator)->withInput()->with('error', "Failed to submit request");
            }
            DB::commit();
            return redirect()->route('user.profile')->withErrors($validator)->with('success', 'KYC request has been submitted.');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')->withErrors($validator)->with('error', $e->getMessage());
        }
    }
    public function addressVerification(Request $request)
    {

        $rules = [];
        $rules['addressProof'] = ['image','mimes:jpeg,jpg,png', 'max:2048'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('addressVerification', '1');
            return back()->withErrors($validator)->withInput();
        }

        $path = config('location.kyc.path').date('Y').'/'.date('m').'/'.date('d');

        $reqField = [];
        try {
            if($request->hasFile('addressProof')){
                $reqField['addressProof'] = [
                    'field_name' => $this->uploadImage($request['addressProof'], $path),
                    'type' => 'file',
                ];
            }else{
                $validator->errors()->add('addressVerification', '1');

                session()->flash('error', 'Please select a ' . 'address Proof');
                return back()->withInput();
            }
        } catch (\Exception $exp) {
            session()->flash('error', 'Could not upload your ' . 'address Proof');
            return redirect()->route('user.profile')->withInput();
        }

        try {

            DB::beginTransaction();
            $user = $this->user;
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = 'address-verification';
            $kyc->details = $reqField;
            $kyc->save();
            $user->address_verify =  1;
            $user->save();

            if(!$kyc){
                DB::rollBack();
                $validator->errors()->add('addressVerification', '1');
                return redirect()->route('user.profile')->withErrors($validator)->withInput()->with('error', "Failed to submit request");
            }
            DB::commit();
            return redirect()->route('user.profile')->withErrors($validator)->with('success', 'Your request has been submitted.');

        } catch (\Exception $e) {
            $validator->errors()->add('addressVerification', '1');
            return redirect()->route('user.profile')->with('error', $e->getMessage())->withErrors($validator);
        }
    }



    public function twoStepSecurity()
    {
        $basic = (object)config('basic');
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $secret);
        $previousCode = $this->user->two_fa_code;

        $previousQR = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $previousCode);
        return view($this->theme . 'user.twoFA.index', compact('secret', 'qrCodeUrl', 'previousCode', 'previousQR'));
    }

    public function twoStepEnable(Request $request)
    {
        $user = $this->user;
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        $userCode = $request->code;
        if ($oneCode == $userCode) {
            $user['two_fa'] = 1;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = $request->key;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_ENABLED', [
                'action' => 'Enabled',
                'code' => $user->two_fa_code,
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);
            return back()->with('success', 'Google Authenticator Has Been Enabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }

    }


    public function twoStepDisable(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $user = $this->user;
        $ga = new GoogleAuthenticator();

        $secret = $user->two_fa_code;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {
            $user['two_fa'] = 0;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = null;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_DISABLED', [
                'action' => 'Disabled',
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);

            return back()->with('success', 'Google Authenticator Has Been Disabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }
    }


    /*
     * User payout Operation
     */
    public function payoutMoney()
    {
        $data['title'] = "Payout Money";
        $data['wallets'] = $this->user->wallets()->with('miner')->get();
        return view($this->theme . 'user.payout.money', $data);
    }

    public function payoutMoneyRequest(Request $request)
    {
        $this->validate($request, [
            'wallet' => 'required|integer',
            'amount' => ['required', 'numeric']
        ]);

        $user = $this->user;
        $userWallet = UserWallet::where('id', $request->wallet)->where('user_id', $user->id)->with('miner')->firstOrFail();

        $finalAmo = $request->amount;

        if ($request->amount < optional($userWallet->miner)->minimum_amount) {
            session()->flash('error', 'Minimum payout Amount ' . round(optional($userWallet->miner)->minimum_amount, 8) . ' ' . $userWallet->code);
            return back()->withInput();
        }
        if ($request->amount > optional($userWallet->miner)->maximum_amount) {
            session()->flash('error', 'Maximum payout Amount ' . round(optional($userWallet->miner)->maximum_amount, 8) . ' ' . $userWallet->code);
            return back()->withInput();
        }

        if (getAmount($finalAmo) > $userWallet->balance) {
            session()->flash('error', 'Insufficient Balance For Payout.');
            return back()->withInput();
        }
        if ($userWallet->wallet_address == '') {
            session()->flash('error', 'Please set up your wallet address.');
            return redirect(route('user.wallet'));
        }

        $userWallet->balance = getAmount($userWallet->balance - $finalAmo);
        $userWallet->save();


        $trx = strRandom();

        $withdraw = new PayoutLog();
        $withdraw->user_id = $user->id;
        $withdraw->method_id = $userWallet->id;
        $withdraw->amount = $finalAmo;
        $withdraw->charge = 0;
        $withdraw->net_amount = $finalAmo;
        $withdraw->information = $userWallet->wallet_address;
        $withdraw->code = $userWallet->code;
        $withdraw->trx_id = $trx;
        $withdraw->status = 1;
        $withdraw->save();


        $remarks = 'Withdraw from ' . optional($userWallet->miner)->name . ' wallet';
        BasicService::makeTransaction($user, $withdraw->amount, $withdraw->charge, '-', $userWallet->code, $withdraw->trx_id, $remarks);


        $this->sendMailSms($user, 'PAYOUT_REQUEST', [
            'method_name' => optional($userWallet->miner)->name,
            'amount' => getAmount($withdraw->amount),
            'charge' => getAmount($withdraw->charge),
            'currency' => $userWallet->code,
            'trx' => $withdraw->trx_id,
        ]);


        $msg = [
            'username' => $user->username,
            'amount' => getAmount($withdraw->amount),
            'currency' => $userWallet->code,
        ];
        $action = [
            "link" => route('admin.user.withdrawal', $user->id),
            "icon" => "fa fa-money-bill-alt "
        ];
        $this->adminPushNotification('PAYOUT_REQUEST', $msg, $action);

        session()->flash('success', 'Payout request Successfully Submitted. Wait For Confirmation.');
        session()->put('wtrx', $trx);
        return redirect()->route('user.payout.preview');

    }


    public function payoutPreview()
    {
        $withdraw = PayoutLog::latest()->where('trx_id', session()->get('wtrx'))->where('user_id', $this->user->id)->latest()->with('method')->firstOrFail();
        $title = "Payout Preview";
        return view($this->theme . 'user.payout.preview', compact('withdraw', 'title'));
    }


    public function payoutHistory()
    {
        $user = $this->user;
        $data['payoutLog'] = PayoutLog::whereUser_id($user->id)->where('status', '!=', 0)->latest()->with('user', 'method')->paginate(config('basic.paginate'));
        $data['title'] = "Payout Log";
        return view($this->theme . 'user.payout.log', $data);
    }


    public function payoutHistorySearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $payoutLog = PayoutLog::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search['name']);
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->with('user', 'method')->paginate(config('basic.paginate'));
        $payoutLog->appends($search);

        $title = "Payout Log";
        return view($this->theme . 'user.payout.log', compact('title', 'payoutLog'));
    }


    public function referral()
    {
        $title = "My Referral";
        $referrals = getLevelUser($this->user->id);;
        return view($this->theme . 'user.referral', compact('title', 'referrals'));
    }

    public function referralBonus()
    {
        $title = "Referral Bonus";
        $transactions = $this->user->referralBonusLog()->latest()->with('bonusBy:id,firstname,lastname')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.referral-bonus', compact('title', 'transactions'));
    }

    public function referralBonusSearch(Request $request)
    {
        $title = "Referral Bonus";
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $transaction = $this->user->referralBonusLog()->latest()
            ->with('bonusBy:id,firstname,lastname')
            ->when(isset($search['search_user']), function ($query) use ($search) {
                return $query->whereHas('bonusBy', function ($q) use ($search) {
                    $q->where(DB::raw('concat(firstname, " ", lastname)'), 'LIKE', "%{$search['search_user']}%")
                        ->orWhere('firstname', 'LIKE', '%' . $search['search_user'] . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search['search_user'] . '%')
                        ->orWhere('username', 'LIKE', '%' . $search['search_user'] . '%');
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transactions = $transaction->appends($search);

        return view($this->theme . 'user.transaction.referral-bonus', compact('title', 'transactions'));
    }

    public function planOrder(Request $request)
    {

        $req = Purify::clean($request->only('plan_id', 'payment_type'));
        $rules = [
            'plan_id' => 'required',
            'payment_type' => ['required', Rule::in([0, 1])],
        ];
        $message = [
            'plan_id.required' => 'Please select a plan.',
            'payment_type.required' => 'Please select a payment type.',
        ];

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;

        $plan = MiningPlan::where('id', $req['plan_id'])->where('status', 1)->with('miner')->firstOrFail();


        if ($req['payment_type'] == '1') {
            if ($user->balance < $plan->price) {
                return back()->with('error', 'Insufficient balance to purchase plan')->withInput();
            }

            $user->balance -= $plan->price;
            $user->save();

            $invest = BasicService::setInvestment($plan, $user);

            BasicService::getWallet($invest);

            $transaction = $invest->transaction;

            $balance_type = config('basic.currency');
            $remarks = $plan->name . ' has been purchased';
            BasicService::makeTransaction($user, $plan->price, 0, '-', $balance_type, $transaction, $remarks);


            $basic = (object)config('basic');

            $this->sendMailSms($user, 'PLAN_PURCHASE', [
                'order_id' => $transaction,
                'amount' => getAmount($plan->price),
                'currency' => $basic->currency_symbol,
                'charge' => 0,
                'plan_name' => $plan->name,
            ]);

            $msg = [
                'username' => $user->username,
                'amount' => getAmount($plan->price),
                'currency' => $basic->currency_symbol,
                'plan_name' => $plan->name
            ];
            $action = [
                "link" => route('admin.user.plan-purchaseLog', $user->id),
                "icon" => "fa fa-money-bill-alt "
            ];
            $this->adminPushNotification('PLAN_PURCHASE', $msg, $action);
            return back()->with('success', 'Plan has been purchased  successfully');
        } else {
            $invest = BasicService::setInvestment($plan, $user, 0);
            session()->put('orderId', $invest->transaction);
            return redirect(route('user.payment'));
        }
    }


    public function planLog()
    {
        $title = "Purchase Log";
        $records = $this->user->investments()->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.purchase', compact('records', 'title'));
    }


    public function myWallets()
    {

        $title = "Wallet Settings";
        $wallets = auth()->user()->wallets()->get();
        return view($this->theme . 'user.wallets', compact('title', 'wallets'));
    }

    public function myWalletsUpdate(Request $request)
    {
        $wallets = UserWallet::where('user_id', $this->user->id)->get();
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


}
