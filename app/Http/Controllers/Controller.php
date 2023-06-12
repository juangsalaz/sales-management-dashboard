<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $notif;
    protected $list_jabatan;

    public function __construct(){
        $notifikasi = new \App\notifikasi();
        $notif = $notifikasi->where('read','=','0')->count();
        $this->notif = $notif;

        $this->list_jabatan = new \App\jabatan();
    }
}
