<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class real_sales extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'bulan',
        'tahun',
        'produk',
        'nama_user',
        'distributor'
      ];
}
