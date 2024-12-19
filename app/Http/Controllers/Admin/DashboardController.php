<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\Fund;
use App\Models\Investment;
use App\Models\MiningPlan;
use App\Models\PayoutLog;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    use Upload;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function forbidden()
    {
        return view('admin.errors.403');
    }

    public function dashboard()
    {
        $data['wallets'] = UserWallet::selectRaw("SUM(balance) as total")->selectRaw("code")->groupBy('code')->get()->toArray();

        $data['funding'] = collect(Fund::selectRaw('SUM(CASE WHEN status = 1 THEN amount END) AS totalAmountReceived')
            ->selectRaw('SUM(CASE WHEN status = 1 THEN charge END) AS totalChargeReceived')
            ->selectRaw('SUM((CASE WHEN created_at = CURDATE() AND status = 1 THEN amount END)) AS todayDeposit')
            ->selectRaw('COUNT((CASE WHEN status = 2 THEN amount END)) AS depositRequest')
            ->get()->toArray())->collapse();

        $data['userRecord'] = collect(User::selectRaw('COUNT(id) AS totalUser')
            ->selectRaw('count(CASE WHEN status = 1  THEN id END) AS activeUser')
            ->selectRaw('SUM(balance) AS totalUserBalance')
            ->selectRaw('COUNT((CASE WHEN created_at = CURDATE()  THEN id END)) AS todayJoin')
            ->get()->makeHidden(['fullname', 'mobile'])->toArray())->collapse();



        $data['tickets'] = collect(Ticket::where('created_at', '>', Carbon::now()->subDays(30))
            ->selectRaw('count(CASE WHEN status = 3  THEN status END) AS closed')
            ->selectRaw('count(CASE WHEN status = 2  THEN status END) AS replied')
            ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS answered')
            ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pending')
            ->get()->toArray())->collapse();


        $data['sales'] = collect(Investment::selectRaw('SUM(CASE WHEN status != 0 THEN price END) AS totalSoldAmount')
            ->selectRaw('COUNT(CASE WHEN status != 0 THEN id END) AS totalSold')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS running')
            ->selectRaw('COUNT(CASE WHEN status = 2 THEN id END) AS complete')
            ->get()->toArray())->collapse();



        $dailyDeposit = $this->dayList();
        Fund::whereMonth('created_at', Carbon::now()->month)
            ->select(
                DB::raw('sum(amount) as totalAmount'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->where('status',1)
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyDeposit) {
                $dailyDeposit->put($item['date'], round($item['totalAmount'], 2));
            });
        $statistics['deposit'] = $dailyDeposit;


        $dailyInvestment = $this->dayList();
        Investment::whereMonth('created_at', Carbon::now()->month)
            ->where('status','!=',0)
            ->select(
                DB::raw('sum(price) as totalAmount'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->where('status',1)
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyInvestment) {
                $dailyInvestment->put($item['date'], round($item['totalAmount'], 2));
            });
        $statistics['investment'] = $dailyInvestment;

        $statistics['schedule'] = $this->dayList();



        /*
       * Pie Chart Data
       */
        $totalSold = $data['sales']['totalSold'];
        $pieLog = collect();
        collect(Investment::with('plan')
            ->whereHas('plan')
            ->where('status','!=',0)
            ->get()
            ->groupBy('plan.name')->toArray())
            ->map(function ($item, $key) use ($totalSold, $pieLog) {
            $pieLog->push(['level' => kebab2Title($key), 'value' => (0 < $totalSold) ? (round(count($item) / $totalSold * 100, 2)) : 0]);

        });


        $data['latestUser'] = User::latest()->limit(5)->get();

        return view('admin.dashboard', $data, compact('statistics', 'statistics','pieLog'));
    }

    public function dayList()
    {
        $totalDays = Carbon::now()->endOfMonth()->format('d');
        $daysByMonth = [];
        for ($i = 1; $i <= $totalDays; $i++) {
            array_push($daysByMonth, ['Day ' . sprintf("%02d", $i) => 0]);
        }
        return collect($daysByMonth)->collapse();
    }

    public function days_in_month($month, $year){
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public function profile()
    {
        $admin = $this->user;
        return view('admin.profile', compact('admin'));
    }


    public function profileUpdate(Request $request)
    {
        $req = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'name' => 'sometimes|required',
            'username' => 'sometimes|required|unique:admins,username,' . $this->user->id,
            'email' => 'sometimes|required|email|unique:admins,email,' . $this->user->id,
            'phone' => 'sometimes|required',
            'address' => 'sometimes|required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;
        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = $this->uploadImage($request->image, config('location.admin.path'), config('location.admin.size'), $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }
        $user->name = $req['name'];
        $user->username = $req['username'];
        $user->email = $req['email'];
        $user->phone = $req['phone'];
        $user->address = $req['address'];
        $user->save();

        return back()->with('success', 'Updated Successfully.');
    }


    public function password()
    {
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $req = Purify::clean($request->all());

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $request = (object)$req;
        $user = $this->user;
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', "Password didn't match");
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        return back()->with('success', 'Password has been Changed');
    }
}
