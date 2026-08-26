<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class CuniAppUser extends Model
{
    protected $connection = 'cuniapp';
    protected $table = 'users';
    protected $guarded = [];
    public $timestamps = true;

    public function firm()
    {
        return $this->belongsTo(CuniAppFirm::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(CuniAppSubscription::class);
    }
}
