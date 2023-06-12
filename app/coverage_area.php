<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class coverage_area extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable=[
        'nama_coverage_area',
        'keterangan',
      ];
}
