<?php

namespace App\Models\SaaS;

use Illuminate\Database\Eloquent\Model;

class ClicTableUser extends Model
{
    protected $connection = 'clictable';
    protected $table = 'users';
    protected $guarded = [];
    public $timestamps = true;
}
