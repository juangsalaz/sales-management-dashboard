<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class distributor_produk extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'data_distributor_id',
        'produk_id',
      ];
}
