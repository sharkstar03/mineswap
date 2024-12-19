<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\MiningList;
use App\Models\MiningPlan;
use App\Models\Referral;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Stevebauman\Purify\Facades\Purify;

class MiningListController extends Controller
{
    use Upload;

    public function index()
    {
        $list = MiningList::withCount('plans')->orderBy('name')->get();
        $page_title = "Miner list";
        return view('admin.mining_list.index', compact('list', 'page_title'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => ['required', 'unique:mining_lists,code'],
            'minimum_amount' => 'required|numeric',
            'maximum_amount' => 'required|numeric',
        ];
        $this->validate($request, $rules);
        DB::beginTransaction();
        try {
            $purifiedData = Purify::clean($request->all());
            $purifiedData = (object)$purifiedData;
            $data['name'] = $purifiedData->name;
            $data['code'] = strtoupper(slug($purifiedData->code));
            $data['minimum_amount'] = $purifiedData->minimum_amount;
            $data['maximum_amount'] = $purifiedData->maximum_amount;
            $data['status'] = $purifiedData->status ?? 0;
            MiningList::create($data);
            DB::commit();
            return back()->with('success', 'Save Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $miningList = MiningList::findOrFail($id);
        $rules = [
            'name' => 'required',
            'code' => ['required', 'unique:mining_lists,code,' . $miningList->id],
            'minimum_amount' => 'required|numeric',
            'maximum_amount' => 'required|numeric',
        ];
        $this->validate($request, $rules);
        DB::beginTransaction();
        try {
            $purifiedData = Purify::clean($request->all());
            $purifiedData = (object)$purifiedData;
            $data['name'] = $purifiedData->name;
            $data['code'] = strtoupper(slug($purifiedData->code));
            $data['minimum_amount'] = $purifiedData->minimum_amount;
            $data['maximum_amount'] = $purifiedData->maximum_amount;
            $data['status'] = $purifiedData->status ?? 0;
            $miningList->update($data);
            DB::commit();
            return back()->with('success', 'Save Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    public function plans()
    {
        $list = MiningPlan::with('miner:id,code')->latest()->get();
        $page_title = "Miner list";
        return view('admin.plan.index', compact('list', 'page_title'));
    }

    public function planCreate()
    {
        $list = MiningList::where('status', 1)->latest()->get();
        $page_title = "Add a plan";
        return view('admin.plan.create', compact('list', 'page_title'));
    }

    public function planStore(Request $request)
    {
        $rules = [
            'name' => 'required',
            'mining_id' => ['required'],
            'price' => 'required|numeric',
            'hash_rate_speed' => 'required|numeric',
            'hash_rate_unit' => 'required',
            'profit' => [ Rule::requiredIf(function () use ($request) {
                return $request->input('profit_type') == '1';
            })],
            'minimum_profit' => [ Rule::requiredIf(function () use ($request) {
                return $request->input('profit_type') == '';
            })],
            'maximum_profit' => [Rule::requiredIf(function () use ($request) {
                return $request->input('profit_type') == '';
            })],
            'duration' => 'required|numeric',
            'period' => 'required',
            'referral' => 'required|numeric',
            'image' => ['nullable']
        ];
        $this->validate($request, $rules);


        DB::beginTransaction();
        try {
            $purifiedData = Purify::clean($request->all());
            $purifiedData = (object)$purifiedData;

            if ($request->hasFile('image')) {
                try {
                    $data['image'] = $this->uploadImage($request->image, config('location.plan.path'), config('location.plan.size'));

                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.')->withInput();
                }
            }
            $data['name'] = $purifiedData->name;
            $data['mining_id'] = $purifiedData->mining_id;
            $data['price'] = $purifiedData->price;
            $data['hash_rate_speed'] = $purifiedData->hash_rate_speed;
            $data['hash_rate_unit'] = $purifiedData->hash_rate_unit;
            $data['minimum_profit'] = isset($purifiedData->profit_type)  ?$purifiedData->profit:$purifiedData->minimum_profit;
            $data['maximum_profit'] = isset($purifiedData->profit_type)  ?$purifiedData->profit : $purifiedData->maximum_profit;
            $data['duration'] = $purifiedData->duration;
            $data['period'] = $purifiedData->period;
            $data['referral'] = $purifiedData->referral;
            $data['status'] = isset($purifiedData->status) ?1 :0;
            $data['featured'] = isset($purifiedData->featured) ?1:0;
            $data['profit_type'] = isset($purifiedData->profit_type) ?1: 0;


            MiningPlan::create($data);

            DB::commit();
            return back()->with('success', 'Save Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();;
        }
    }

    public function planEdit(MiningPlan $miningPlan)
    {
        $list = MiningList::latest()->get();
        $page_title = "Edit Plan";
        return view('admin.plan.edit', compact('list','miningPlan','page_title'));
    }

    public function planUpdate(Request $request, MiningPlan $miningPlan)
    {

        $rules = [
            'name' => 'required',
            'mining_id' => ['required'],
            'price' => 'required|numeric',
            'hash_rate_speed' => 'required|numeric',
            'hash_rate_unit' => 'required',
            'profit' => [ Rule::requiredIf(function () use ($request) {
                return $request->input('profit_type') == '1';
            })],
            'minimum_profit' => [ Rule::requiredIf(function () use ($request) {
                return $request->input('profit_type') == '';
            })],
            'maximum_profit' => [Rule::requiredIf(function () use ($request) {
                return $request->input('profit_type') == '';
            })],
            'duration' => 'required|numeric',
            'period' => 'required',
            'referral' => 'required',
            'image' => ['nullable']
        ];
        $this->validate($request, $rules);


//        DB::beginTransaction();
//        try {
            $purifiedData = Purify::clean($request->all());
            $purifiedData = (object)$purifiedData;

            if ($request->hasFile('image')) {
                try {
                    $data['image'] = $this->uploadImage($request->image, config('location.plan.path'), config('location.plan.size'), $miningPlan->image);

                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.')->withInput();
                }
            }
            $data['name'] = $purifiedData->name;
            $data['mining_id'] = $purifiedData->mining_id;
            $data['price'] = $purifiedData->price;
            $data['hash_rate_speed'] = $purifiedData->hash_rate_speed;
            $data['hash_rate_unit'] = $purifiedData->hash_rate_unit;
            $data['minimum_profit'] = isset($purifiedData->profit_type)  ?$purifiedData->profit:$purifiedData->minimum_profit;
            $data['maximum_profit'] = isset($purifiedData->profit_type)  ?$purifiedData->profit : $purifiedData->maximum_profit;
            $data['duration'] = $purifiedData->duration;
            $data['period'] = $purifiedData->period;
            $data['referral'] = $purifiedData->referral;
            $data['status'] = isset($purifiedData->status) ?1 :0;
            $data['featured'] = isset($purifiedData->featured) ?1:0;
            $data['profit_type'] = isset($purifiedData->profit_type) ?1: 0;

            $miningPlan->fill($data)->save();

            return back()->with('success', 'Update Successfully');

    }


    public function referralCommission()
    {
        $referrals = Referral::get();
        return view('admin.mining_list.referral-commission', compact('referrals'));

    }

    public function referralCommissionStore(Request $request)
    {
        $request->validate([
            'level*' => 'required|integer|min:1',
            'percent*' => 'required|numeric',
            'commission_type' => 'required',
        ]);

        Referral::where('commission_type',$request->commission_type)->delete();

        for ($i = 0; $i < count($request->level); $i++){
            $referral = new Referral();
            $referral->commission_type = $request->commission_type;
            $referral->level = $request->level[$i];
            $referral->percent = $request->percent[$i];
            $referral->save();
        }

        return back()->with('success', 'Level Bonus Has been Updated.');
    }

}
