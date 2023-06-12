<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class distributor extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'nama_distributor',
      ];
}
