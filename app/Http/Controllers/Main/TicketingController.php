<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Uuid;
use Datatables;
use Carbon\Carbon;

use App\Models\Ticketing;

class TicketingController extends Controller
{
    public function lists()
    {
        return view('content.main.ticket.index');
    }

    public function data(Request $request)
    {
        if(request()->ajax()) {
            return datatables()->of(Ticketing::select('*'))
            ->addColumn('action', function($row){
                return $this->_button('active', $row->ticket_id);                        
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
                '<a class="dropdown-item" href="'.url('ticket/update/'.$id).'"><i class="bx bx-edit-alt me-2"></i> Edit</a>'.
                '<a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i> Delete</a>'.
                '</div>'.
            '</div>';
    }

    public function create()
    {
        return view('content.main.ticket.create');
    }

    public function createNewTicketID()
    {
        try {
            $year = Carbon::now()->format('Y');
            $id = 'TCK'.$year.'-000001';
            $maxId = Ticketing::withTrashed()
             
            ->where('ticket_id', 'LIKE', 'TCK'.$year.'-%')->max('ticket_id');
            if (!$maxId) {
                $id = 'TCK'.$year.'-000001';
            } else {
                $maxId = str_replace('TCK'.$year.'-', '', $maxId);
                $count = $maxId + 1;

                if ($count < 10) { $id = 'TCK'.$year.'-00000' . $count; } 
                elseif ($count >= 10 && $count < 100) { $id = 'TCK'.$year.'-0000' . $count; } 
                elseif ($count >= 100 && $count < 1000) { $id = 'TCK'.$year.'-000' . $count; } 
                elseif ($count >= 1000 && $count < 10000) { $id = 'TCK'.$year.'-00' . $count; } 
                elseif ($count >= 10000) { $id = 'TCK'.$year.'-0' . $count; } 
                else { $id = 'TCK'.$year.'-' . $count; }
            }
            return $id;
        } catch (\Exception $e) {
            Log::error('Error make ticket id: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Variabel untuk seluruh aktivitas
            $ticket_id = $this->createNewTicketID();

            // Membuat folder sesuai dengan ID Ticket
            $path = '/storage/data/'.$ticket_id;
            if(!File::exists($path)) {
                File::makeDirectory($path, $mode = 0755, true, true);
            }

            // Membuat JSON evidance
            $json_evidance = array();
            if($request->evidance){
                $i = 0;
                foreach($request->evidance as $key => $value){
                    $nama_file = Uuid::uuid4()->getHex()->toString();
                    $extFile   = $value->getClientOriginalExtension();   
                    $path      = 'storage/data/'.$ticket_id.'/'.$nama_file.'.'.$extFile;
                    $json_evidance[$i] = [
                        "filename" => $nama_file.'.'.$extFile,
                        "evidance" => $path,
                        "description" => $request->keterangan[$i]
                    ];
                    $i++;
                }
            }

            // Menyimpan data ke dalam database
            $newTicket                  = new Ticketing;
            $newTicket->ticket_id       = $this->createNewTicketID();
            $newTicket->subject         = $request->subject;
            $newTicket->description     = $request->description;
            $newTicket->ticket_date     = $request->ticket_date;
            $newTicket->evidance        = json_encode($json_evidance); // JSON
            $newTicket->requester_id    = Auth::user()['id'];
            $newTicket->requester_name  = Auth::user()['username'];
            $newTicket->created_at      = Carbon::now();
            $newTicket->save();

            if(!$newTicket){
                return redirect()->back()->withInput()->with('error','Ada kesalahan saat menyimpan Tiket.');
            }

            for($i=0;$i<count($json_evidance);$i++){
                $extFile  = substr(strrchr($json_evidance[$i]['filename'],'.'),1);
                $imgArray = ['png','jpg','jpeg'];
                if (!in_array($extFile,$imgArray)) {
                    return redirect()->back()->withInput()->with('error','Format file tidak didukung. File harus berformat jpg, png, atau jpeg.');
                }
                $path = $request->evidance[$i]->storeAs('public/data/'.$ticket_id, $json_evidance[$i]['filename']);
            }

            return redirect()->route('ticket.create')->with('success','Berhasil!. Tiket telah dibuat.');

        } catch (\Exception $e) {
            Log::error('Error storing ticket: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $data['data'] = Ticketing::where('ticket_id',$id)->first();
            $data['evidance'] = json_decode($data['data']['evidance']);
            return view('content.main.ticket.update', $data);

        } catch (\Exception $e) {
            Log::error('Error editing ticket: ' . $e->getMessage());
            return back()->with('error',$e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {

            $ticket_id = $request->ticket_id;
            $data = Ticketing::where('ticket_id',$ticket_id)->first();
            $fotoEvidance = json_decode($data->evidance);
            $json_evidance = array();

            // Membuat JSON Evidance baru dengan foto atau keterangan baru
            for($i=0;$i<count(json_decode($data->evidance));$i++){
                $nama_file = $fotoEvidance[$i]->filename;
                $path = 'storage/data/'.$ticket_id.'/'.$nama_file;
                if(array_key_exists($i,$request->evidance)) {
                    $filename = $request->file('evidance')[$i]->getClientOriginalName();
                    $extFile   = substr(strrchr($filename,'.'),1);
                    $extFileOri = explode(".",$nama_file);
                    $path      = 'storage/data/'.$ticket_id.'/'.$extFileOri[0].'.'.$extFile;
                    $json_evidance[$i] = [
                        "filename" => $extFileOri[0].'.'.$extFile,
                        "evidance" => $path,
                        "description" => $request->keterangan[$i]
                    ];
                    if(File::exists(public_path('storage/data/'.$ticket_id.'/'.$nama_file))){
                        File::delete(public_path('storage/data/'.$ticket_id.'/'.$nama_file));
                    }
                } else {
                    $json_evidance[$i] = [
                        "filename" => $nama_file,
                        "evidance" => $path,
                        "description" => $request->keterangan[$i]
                    ];
                }
            }

           
            // Mengubah data ke dalam database
            $data->subject         = $request->subject;
            $data->description     = $request->description;
            $data->ticket_date     = $request->ticket_date;
            $data->evidance        = json_encode($json_evidance); // JSON
            $data->requester_id    = Auth::user()['id'];
            $data->requester_name  = Auth::user()['username'];
            $data->updated_at      = Carbon::now();
            $data->save();

            if(!$data){
                return redirect()->back()->withInput()->with('error','Ada kesalahan saat menyimpan Tiket.');
            }
            
            // Looping evidance
            for($i=0;$i<count($json_evidance);$i++){

                // Cek extensi
                $extFile  = substr(strrchr($json_evidance[$i]['filename'],'.'),1);
                $imgArray = ['png','jpg','jpeg'];
                if (!in_array($extFile,$imgArray)) {
                    return redirect()->back()->withInput()->with('error','Format file tidak didukung. File harus berformat jpg, png, atau jpeg.');
                }

                // Memindahkan gambar ke folder public->storage->data
                if(array_key_exists($i,$request->evidance)){
                    $path = $request->evidance[$i]->storeAs('public/data/'.$ticket_id, $json_evidance[$i]['filename']);
                }
            }

            return redirect()->route('ticket.update',$ticket_id)->with('success','Berhasil!. Tiket telah diubah.')->with(['data' => $data]);

        } catch (\Exception $e) {
            Log::error('Error storing ticket: ' . $e->getMessage());
            // echo "error : ". $e->getMessage();
            return back()->with('error',$e->getMessage());
        }
    }

    public function status(Request $request)
    {
        
    }

    public function pic(Request $request)
    {
        
    }
}
