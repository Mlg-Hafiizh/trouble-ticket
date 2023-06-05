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

use App\Models\Categorization;

class CategorizationController extends Controller
{
    public function lists()
    {
        return view('content.master.categorization.index');
    }

    public function data(Request $request)
    {
        if(request()->ajax()) {
            return datatables()->of(Categorization::select('*'))
                ->addColumn('action', function($row){
                    return $this->_button('active', $row->category);                        
                })
                ->editColumn('updated_at', function($row){
                    return date('d F Y h:m:s',strtotime($row['updated_at']));
                })

                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    private function _button($status = null, $id)
    {
        return $icon_list = ''.
            '<div class="dropdown">'.
                '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>'.
                '<div class="dropdown-menu">'.
                '<a class="dropdown-item" href="'.url('categorization/update/'.$id).'"><i class="bx bx-edit-alt me-2"></i> Edit</a>'.
                '<a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i> Delete</a>'.
                '</div>'.
            '</div>';
    }

    public function create()
    {
        return view('content.master.categorization.create');
    }

    public function createNewCategorizationID()
    {
        try {
            $year = Carbon::now()->format('Y');
            $id = 'CAT'.$year.'-000001';
            $maxId = Categorization::withTrashed()
             
            ->where('category', 'LIKE', 'CAT'.$year.'-%')->max('category');
            if (!$maxId) {
                $id = 'CAT'.$year.'-000001';
            } else {
                $maxId = str_replace('CAT'.$year.'-', '', $maxId);
                $count = $maxId + 1;

                if ($count < 10) { $id = 'CAT'.$year.'-00000' . $count; } 
                elseif ($count >= 10 && $count < 100) { $id = 'CAT'.$year.'-0000' . $count; } 
                elseif ($count >= 100 && $count < 1000) { $id = 'CAT'.$year.'-000' . $count; } 
                elseif ($count >= 1000 && $count < 10000) { $id = 'CAT'.$year.'-00' . $count; } 
                elseif ($count >= 10000) { $id = 'CAT'.$year.'-0' . $count; } 
                else { $id = 'CAT'.$year.'-' . $count; }
            }
            return $id;
        } catch (\Exception $e) {
            Log::error('Error make categorization id: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Variabel untuk seluruh aktivitas
            $category = $this->createNewUserID();

            // Cek Email 
            $cekEmail = Categorization::where('email', $request->email)->first();
            if($cekEmail){
                return redirect()->back()->withInput()->with('error','Email '.$request->email.' sudah ada yang menggunakan.');
            }
            
            // Cek Username
            $cekUsername = Categorization::where('email', $request->username)->first();
            if($cekUsername){
                return redirect()->back()->withInput()->with('error','Username '.$request->username.' sudah ada yang menggunakan.');
            }

            $newUser = new User();
            $newUser->category   = $this->createNewUserID();
            $newUser->name      = $request->name;
            $newUser->username  = $request->username;
            $newUser->email     = $request->email;
            $newUser->password  = Hash::make($request->password);
            $newUser->save();

            if(!$newUser){
                return redirect()->back()->withInput()->with('error','Ada kesalahan saat menyimpan categorization.');
            }

            return redirect()->route('categorization.create')->with('success','Berhasil!. User telah dibuat.');

        } catch (\Exception $e) {
            Log::error('Error storing user: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $data['data'] = Categorization::where('category',$id)->first();
            return view('content.master.categorization.update', $data);

        } catch (\Exception $e) {
            Log::error('Error editing user: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {

            // Cek Email 
            $cekEmail = Categorization::where('email', $request->email)
                ->where('category','!=', $request->category)
                ->first();
                if($cekEmail){
                    return redirect()->back()->withInput()->with('error','Email '.$request->email.' sudah ada yang menggunakan.');
                }
                
            // Cek Username
            $cekUsername = Categorization::where('email', $request->username)
                ->where('category','!=', $request->category)
                ->first();
            if($cekUsername){
                return redirect()->back()->withInput()->with('error','Username '.$request->username.' sudah ada yang menggunakan.');
            }

            $data = Categorization::where('category',$request->category)->first();

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
                return redirect()->back()->withInput()->with('error','Ada kesalahan saat mengubah categorization.');
            }

            return redirect()->route('categorization.update',$request->category)->with('success','Berhasil!. User telah diubah.')->with(['data' => $data]);

        } catch (\Exception $e) {
            Log::error('Error editing user: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function inactive(Request $request)
    {
        
    }
}
