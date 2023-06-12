<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notifikasi extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'id_user',
        'id_estimasi',
        'id_real_sales',
        'read'
      ];
}
