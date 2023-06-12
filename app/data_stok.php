<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class data_stok extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'produk',
        'user',
        'area',
        'distributor',
        'cabang',
        'kuantiti'
      ];
}
