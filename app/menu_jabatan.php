<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu_jabatan extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'id_jabatan',
        'id_menu',
        'id_sub_menu1',
        'id_sub_menu2',
      ];
}
