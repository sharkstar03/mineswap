<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPlan extends Model
{
    use HasFactory;
    protected $table = "mining_plans";
    protected $guarded = ['id'];
    protected $appends = ['periodText'];

    public function miner()
    {
        return $this->belongsTo(MiningList::class, 'mining_id','id');
    }
    public function getPeriodTextAttribute(){
        foreach(config('plan.period') as $key => $item){
            if($this->period == $item){
                return $key;
            }
        }
    }

    public function scopePeriodName()
    {
        if( array_search( $this->period ,config('plan.period') ) )
        {
            return key(config('plan.period'));
        }
    }
}
