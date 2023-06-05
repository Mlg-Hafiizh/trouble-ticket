@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account/</span> Edit informasi pribadi</h4>

<form action="{{ route('acc.edit') }}" method="POST">
  <input type="hidden" name="user_id" value="{{ $data->user_id }}">
  @csrf
  <div class="row">
    <div class="col-md-6">
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
            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label" for="username">Username</label>
                <div class="input-group input-group-merge">
                  <input type="text" class="form-control" id="username" name="username" value="{{ $data->username }}" placeholder="Username" required/>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label" for="name">Full Name</label>
                <div class="input-group input-group-merge">
                  <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}" placeholder="Full name" />
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="{{ $data->email }}" placeholder="example@example.com" required/>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-12">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="***"/>
              </div>
            </div>
          </div>
          <div class="row ">
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary">Send</button>
              <button type="button" class="btn btn-danger">Reset</button>
              <a href="{{ url('user/lists') }}"><button type="button" class="btn btn-warning">Back</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
  // 
</script>
@endsection
