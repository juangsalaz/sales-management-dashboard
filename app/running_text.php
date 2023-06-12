<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class running_text extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'teks',
      ];
}
