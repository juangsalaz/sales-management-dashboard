<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sub_menu1 extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'nama',
        'id_menu_utama',
        'urutan'
      ];
}
