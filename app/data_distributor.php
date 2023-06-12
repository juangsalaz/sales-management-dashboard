<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class data_distributor extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'users',
      ];
}
