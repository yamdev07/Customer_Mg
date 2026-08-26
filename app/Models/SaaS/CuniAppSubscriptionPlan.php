<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class CuniAppSubscriptionPlan extends Model
{
    protected $connection = 'cuniapp';
    protected $table = 'subscription_plans';
    protected $guarded = [];
    public $timestamps = true;
}
