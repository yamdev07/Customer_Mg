<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class HotelCustomer extends Model
{
    protected $connection = 'hotel';
    protected $table = 'customers';
    protected $guarded = [];
    public $timestamps = true;
}
