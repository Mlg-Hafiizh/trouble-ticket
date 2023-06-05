<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Datatables;
use Carbon\Carbon;

use App\Models\User;

class AccountController extends Controller
{
    public function update($id)
    {
        try {
            $data['data'] = User::where('user_id',$id)->first();
            return view('content.master.account.update', $data);

        } catch (\Exception $e) {
            Log::error('Error editing account '.$id.': ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {

            // Cek Email 
            $cekEmail = User::where('email', $request->email)
                ->where('user_id','!=', $request->user_id)
                ->first();
                if($cekEmail){
                    return redirect()->back()->withInput()->with('error','Email '.$request->email.' sudah ada yang menggunakan.');
                }
                
            // Cek Username
            $cekUsername = User::where('email', $request->username)
                ->where('user_id','!=', $request->user_id)
                ->first();
            if($cekUsername){
                return redirect()->back()->withInput()->with('error','Username '.$request->username.' sudah ada yang menggunakan.');
            }

            $data = User::where('user_id',$request->user_id)->first();

            // Mengubah data ke dalam database
            $data->name       = $request->name;
            $data->username   = $request->username;
            $data->email      = $request->email;
            
            // Jika ada password yang diperbaharui
            if($request->password){
                $data->password  = Hash::make($request->password);
            }
            
            $data->updated_at = Carbon::now();
            $data->save();

            if(!$data){
                return redirect()->back()->withInput()->with('error','Ada kesalahan saat mengubah User.');
            }

            return redirect()->route('user.update',$request->user_id)->with('success','Berhasil!. User telah diubah.')->with(['data' => $data]);

        } catch (\Exception $e) {
            Log::error('Error editing user: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

}
