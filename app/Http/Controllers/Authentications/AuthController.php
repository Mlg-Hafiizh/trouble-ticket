<?php

namespace App\Http\Controllers\Authentications;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Datatables;
use Carbon\Carbon;

use App\Models\User;

class AuthController extends Controller
{
  public function __construct()
  {
      $this->middleware('guest')->except([
          'logout', 'dashboard'
      ]);
  }
  
  public function loginPage()
  {
    if(Auth::check()) {
      $user = Auth::user();
      return redirect()->with('success','Selamat datang kembali. Anda sudah melakukan login.');
    }
    return view('content.authentications.auth.auth-login-basic');
  }

  public function login(Request $request)
    {
      try {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
 
        return redirect()->with('success','Login berhasil');

      } catch (\Exception $e) {

        Log::error('Error login : ' . $e->getMessage());
        return back()->with('error',$e->getMessage());
      }
    }

  public function logout(Request $request)
  {
    $request->session()->flush();
    Auth::logout();
    return redirect('auth/login');
  }

  public function registerPage()
  {
    return view('content.authentications.auth.auth-register-basic');
  }
  
  public function register(Request $request)
  {
    try {
      // Cek Email 
      $cekEmail = User::where('email', $request->email)->first();
      if($cekEmail){
          return redirect()->back()->withInput()->with('error','Email '.$request->email.' sudah ada yang menggunakan.');
      }
      
      // Cek Username
      $cekUsername = User::where('email', $request->username)->first();
      if($cekUsername){
          return redirect()->back()->withInput()->with('error','Username '.$request->username.' sudah ada yang menggunakan.');
      }

      $user = new User();
      $user->user_id = $this->createNewUserID();
      $user->username = $request->username;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->save();

      if(!$user){
        return redirect()->back()->withInput()->with('error','Ada kesalahan saat menyimpan User. Silahkan cek kembali apakah email ada yang sama.');
      }
      
      return back()->with('success','Register successfully.');

    } catch (\Exception $e) {
      Log::error('Error storing user: ' . $e->getMessage());
      return back()->with('error',$e->getMessage());
    }
  }

  public function createNewUserID(){
    try {
        $year = Carbon::now()->format('Y');
        $id = 'USR'.$year.'-000001';
        $maxId = User::withTrashed()
            ->where('user_id', 'LIKE', 'USR'.$year.'-%')->max('user_id');
        if (!$maxId) {
            $id = 'USR'.$year.'-000001';
        } else {
            $maxId = str_replace('USR'.$year.'-', '', $maxId);
            $count = $maxId + 1;

            if ($count < 10) { $id = 'USR'.$year.'-00000' . $count; } 
            elseif ($count >= 10 && $count < 100) { $id = 'USR'.$year.'-0000' . $count; } 
            elseif ($count >= 100 && $count < 1000) { $id = 'USR'.$year.'-000' . $count; } 
            elseif ($count >= 1000 && $count < 10000) { $id = 'USR'.$year.'-00' . $count; } 
            elseif ($count >= 10000) { $id = 'USR'.$year.'-0' . $count; } 
            else { $id = 'USR'.$year.'-' . $count; }
        }
        return $id;
    } catch (\Exception $e) {
        Log::error('Error make user id: ' . $e->getMessage());
        return back()->with('error',$e->getMessage());
    }
}

  /**
   * Fitur Konfirmasi adalah konfirmasi email user setelah user didaftarkan
   * Fungsi-fungsi terkait : confirmPage, confirm
   * Fitur Konfirmasi Start
   */
  public function confirmPage()
  {

  }

  
  public function confirm()
  {

  }
  /**
   * Fitur Konfirmasi End
   */

  public function rememberMe()
  {

  }

  public function forgotPassowrd()
  {

  }

}
