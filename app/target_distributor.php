<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class target_distributor extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'bulan',
        'tahun',
        'distributor',
        'user',
        'area',
        'target'
      ];
}
