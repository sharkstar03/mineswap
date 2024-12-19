<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningList extends Model
{
    use HasFactory;
    protected $table = "mining_lists";
    protected $guarded = ['id'];

    public function plans()
    {
        return $this->hasMany(MiningPlan::class, 'mining_id');
    }


}
