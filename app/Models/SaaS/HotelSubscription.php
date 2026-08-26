<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class HotelSubscription extends Model
{
    protected $connection = 'hotel';
    protected $table = 'subscriptions';
    protected $guarded = [];
    public $timestamps = true;
}
