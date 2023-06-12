<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\daftar_user;

class LoginController extends Controller
{
    private $user;
    public function __construct(daftar_user $user){
        $this->user=$user;
    }
    public function index(){
        return view('login');
    }
    public function autentikasi(Request $request){
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('username', 'password');
        if (Auth::guard('pengguna')->attempt($credentials)) {
            return redirect()->intended('/admin/dashboard');
        } else {
            return redirect('/login');
        }
    }
    public function logout()
    {
      if (Auth::guard('pengguna')->check()) {
        Auth::guard('pengguna')->logout();
      }
      return redirect('/login');
    }
}
