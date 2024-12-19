<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = "investments";

    protected $casts = [
        'plan_info' => 'object'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function plan()
    {
        return $this->belongsTo(MiningPlan::class,'plan_id');
    }

    public function miner()
    {
        return $this->belongsTo(MiningList::class,'code','code');
    }
}
