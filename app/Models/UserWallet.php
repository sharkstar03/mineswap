<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "user_wallets";

    public function miner(){
        return $this->belongsTo(MiningList::class, 'code','code');
    }
}
