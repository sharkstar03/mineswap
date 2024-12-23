<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutLog extends Model
{
    protected $guarded = ['id'];
    protected $table = 'payout_logs';


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function method()
    {
        return $this->belongsTo(Investment::class, 'method_id');
    }
    public function wallet()
    {
        return $this->belongsTo(UserWallet::class, 'method_id');
    }

}
