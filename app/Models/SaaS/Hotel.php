<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $connection = 'hotel';
    protected $table = 'hotels';
    protected $guarded = [];
    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(HotelUser::class);
    }

    public function customers()
    {
        return $this->hasMany(HotelCustomer::class);
    }
}
