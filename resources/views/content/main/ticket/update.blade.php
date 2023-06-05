@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ticket/</span> Ubah tiket</h4>
<form action="{{ route('ticket.edit') }}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="ticket_id" value="{{ $data->ticket_id }}">
  @csrf
  <div class="row">
    <div class="col-xxl">
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Detail Informasi</h5> <small class="text-muted float-end">* berikan informasi selengkap-lengkapnya</small>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success" role="alert">
              {{ session('success') }}
            </div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger" role="alert">
              {{ session('error') }}
            </div>
          @endif
          <div class="row mb-3">
            <div class="col-sm-6">
              <div class="row mb-3">
                <label class="form-label" for="subject">Subjek</label>
                <div class="input-group input-group-merge">
                  <input type="text" class="form-control" id="subject" name="subject" value="{{ $data->subject }}" placeholder="Subject yang akan dibahas" />
                </div>
              </div>
              <div class="row mb-3">
                  <div class="col-sm-6">
                    <label class="form-label" for="ticket_date">Tanggal Tiket</label>
                    <input class="form-control" type="date" id="ticket_date" name="ticket_date" value="{{ $data->ticket_date }}"/>
                  </div>
                  <div class="col-sm-6">
                    <label class="form-label" for="requester">Pengirim</label>
                    <input type="text" class="form-control" id="requester" name="requester" value="{{ $data->requester_name }}" readonly/>
                  </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="row mb-3">
                <label class="form-label" for="basic-default-company">Deskripsi</label>
                <div class="col-sm-11">
                  <textarea id="description" name="description" style="max-height:125px;height:125px;" class="form-control" placeholder="Dekripsi masalah ditulis disini">{{ $data->description }}</textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="row ">
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary">Send</button>
              <button type="button" class="btn btn-danger">Reset</button>
              <a href="{{ url('ticket/lists') }}"><button type="button" class="btn btn-warning">Back</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-xxl">
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Bukti Gambar dilengkapi Ketarangan</h5> <small class="text-muted float-end"></small>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            @php $i=1; @endphp
            @foreach($evidance as $value)
              <div class="col-md-4" style="margin-top:10px;">
                <div class="col-md-12 mb-2">
                  <img id="imgevidance{{ $i }}" style="height:315px;width:100%;border-radius:10px 10px 0px 0px;" src="{{ url($value->evidance) }}" alt="preview image">
                  <div class="alert alert-dark " style="width:100%;border-radius:0px 0px 10px 10px;" id="preview_filename">{{ $value->filename }}</div>
                </div>
                <div class="col-md-12 mb-2">
                  <div class="row" style="margin-bottom:12px;">
                    <div class="col-xxl">
                      <input class="form-control" type="file" id="evidance{{ $i }}" name="evidance[]" onchange="myFunction(this)">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xxl">
                      <label class="form-label" for="basic-icon-default-message">Keterangan</label>
                      <div class="input-group input-group-merge">
                        <textarea id="keterangan" name="keterangan[]" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?" aria-label="Hi, Do you have a moment to talk Joe?" aria-describedby="basic-icon-default-message2">{{ $value->description }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @php $i=$i+1; @endphp
            @endforeach
            <button class="btn btn-info" style="width:100%;"> Tambah Bukti gamber </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
      
  function myFunction(elem) {
    let reader = new FileReader();
      let div_filename = document.getElementById('preview_filename');
      let div_evidance = document.getElementById(elem.id);
      reader.onload = (e) => { 
        $('#img'+elem.id).attr('src', e.target.result); 
      }
      reader.readAsDataURL(div_evidance.files[0]); 
      div_filename.innerHTML = event.target.files[0].name;
  }

  function cuurentDate(elem){
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;
    var today = year + "-" + month + "-" + day;       
    elem.attr("value", today);
  }

  $(document).ready(function() {
    cuurentDate($("#ticket_date"));
  });
</script>
@endsection
