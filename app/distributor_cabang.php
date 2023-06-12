<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class distributor_cabang extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'data_distributor_id',
        'cabang_id',
      ];
}
