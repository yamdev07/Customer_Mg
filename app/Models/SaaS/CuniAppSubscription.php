<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class CuniAppSubscription extends Model
{
    protected $connection = 'cuniapp';
    protected $table = 'subscriptions';
    protected $guarded = [];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(CuniAppUser::class);
    }

    public function plan()
    {
        return $this->belongsTo(CuniAppSubscriptionPlan::class, 'subscription_plan_id');
    }
}
