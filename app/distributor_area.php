<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class distributor_area extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'data_distributor_id',
        'coverage_area_id',
      ];
}
