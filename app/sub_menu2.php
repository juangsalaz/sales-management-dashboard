<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sub_menu2 extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'nama',
        'id_sub_menu1',
      ];
}
