<?php

namespace App\Console\Commands;

use App\Models\Configure;
use App\Models\Investment;
use App\Models\UserWallet;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Facades\App\Services\BasicService;

class Cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron for Mining Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $configure = Configure::firstOrNew();
        $configure->last_return_date = Carbon::now()->toDateTimeString();
        $configure->save();

        Investment::where('status', 1)
            ->with('user')
            ->whereHas('user')
            ->where('formerly', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            ->get()
            ->map(function ($item){
                $interest   = rand($item->minimum_profit*100000000, $item->maximum_profit*100000000)/100000000;
                $wallet = UserWallet::firstOrCreate([
                    'user_id' => $item->user_id,
                    'code' => strtoupper($item->code)
                ]);

                $wallet->balance  += $interest;
                $wallet->update();

                $item->remaining_cycle   -=1;
                $item->formerly       = Carbon::now();
                $item->update();

                $user = $item->user;
                $remarks = "Daily Revenue from ".@$item->plan_info->Name;
                BasicService::makeTransaction($user, $interest, 0, '+', strtoupper($item->code), strRandom(), $remarks);

                if($item->remaining_cycle == 0){
                    $item->status  = 2;
                    $item->update();
                }
            });
        $this->info('status');
    }

}
