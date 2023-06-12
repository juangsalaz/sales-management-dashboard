<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class daftar_user extends Model implements Authenticatable
{
    use SoftDeletes;
    protected $table = 'daftar_users';
    public $incrementing = false;
    protected $fillable=[
        'nama_user',
        'username',
        'bagian',
        'jabatan',
        'status'
      ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
    public function getAuthIdentifierName(){
        return 'username';
    }
    public function getAuthIdentifier(){
        return $this->username;
    }
    public function getAuthPassword(){
        return $this->password;
    }
    public function getRememberToken(){
        return $this->remember_token;
    }
    public function setRememberToken($value){
        $this->remember_token = $value;
    }
    public function getRememberTokenName(){
        return 'remember_token';
    }
}
