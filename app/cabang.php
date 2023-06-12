<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cabang extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'nama_cabang',
        'keterangan',
      ];
}
