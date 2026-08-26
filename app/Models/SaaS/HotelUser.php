<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class HotelUser extends Model
{
    protected $connection = 'hotel';
    protected $table = 'users';
    protected $guarded = [];
    public $timestamps = true;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
