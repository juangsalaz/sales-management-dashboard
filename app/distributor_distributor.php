<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class distributor_distributor extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'data_distributor_id',
        'distributor_id',
      ];
}
