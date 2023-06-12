<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class coverage_area_user extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'user_id',
        'coverage_area_id',
    ];
}
