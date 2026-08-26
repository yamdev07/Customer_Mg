<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class CuniAppFirm extends Model
{
    protected $connection = 'cuniapp';
    protected $table = 'firms';
    protected $guarded = [];
    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(CuniAppUser::class);
    }
}
