<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class produk_cabang extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'produk_id',
        'coverage_area_id',
        'cabang_id',
        'jumlah'
      ];
}
