@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
<style>
  .btn-table-left{
    float:left;
  }
  .btn-table-right{
    float:right;
  }
</style>

<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Tables /</span> User
</h4>

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">Table Basic</h5>
  <div class="table-responsive text-nowrap" style="margin:0em 2em;min-height:500px;">
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
    <table class="table hover display" style="width:100%;" id="datatable-crud">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Username</th>
          <th>Updated at</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        {{-- Ajax Request --}}
      </tbody>
    </table>
  </div>
</div>
<!--/ Basic Bootstrap Table -->

<hr class="my-5">
<script type="text/javascript">
  $(document).ready( function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $('#datatable-crud').DataTable({
      dom: 'Bfrtip',
      buttons: [
          {
              text: 'Add Users',
              className: 'btn btn-success btn-table-left',
              action: function ( e, dt, node, config ) {
                window.location.replace('create');
              }
          },
          // {
          //     text: 'Search',
          //     className: 'btn btn-success btn-table-right',
          //     action: function ( e, dt, node, config ) {
          //         alert( 'Button activated' );
          //     }
          // },
      ],
      processing: true,
      serverSide: true,
      ajax: "{{ url('user/data') }}",
      columns: [
      { data: 'user_id', name: 'user_id', className: '' },
      { data: 'name', name: 'name' },
      { data: 'username', name: 'username' },
      { data: 'updated_at', name: 'updated_at' },
      {data: 'action', name: 'Action', orderable: false},
      ],
      order: [[0, 'desc']]
    });
    $('body').on('click', '.delete', function () {
      if (confirm("Delete Record?") == true) {
        var id = $(this).data('user_id');
        // ajax
        $.ajax({
          type:"POST",
          url: "{{ url('user/inactive') }}",
          data: { user_id: id},
          dataType: 'json',
          success: function(res){
            var oTable = $('#datatable-crud').dataTable();
            oTable.fnDraw(false);
          },
        });
      }
    });
  });
</script>
@endsection
