<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu_utama extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'nama',
        'status',
        'slug',
        'urutan'
      ];
}
