<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pengingat extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'pengingat',
        'keterangan',
        'tanggal',
        'jam'
      ];
}
