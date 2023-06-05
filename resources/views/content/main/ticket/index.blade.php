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
  .dt-button{
    margin-right: 10px;
  }
</style>

<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Tables /</span> Basic Tables
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
          <th>ID</th>
          <th>Judul</th>
          <th>Tanggal Ticket</th>
          <th>Requester</th>
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
    var table =  $('#datatable-crud').DataTable({
      dom: 'Bfrtip',
      buttons: [
          {
              text: 'Add Ticket',
              className: 'btn btn-success btn-table-left',
              action: function ( e, dt, node, config ) {
                window.location.replace('create');
              }
          },
          {
              text: 'Assigment Ticket',
              className: 'btn btn-info',
              action: function ( e, dt, node, config ) {
                window.location.replace('create');
              }
          },
          {
              text: 'Settings',
              className: 'btn btn-primary',
              action: function ( e, dt, node, config ) {
                window.location.replace('create');
              }
          },
          // Custom Search
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
      ajax: "{{ url('ticket/data') }}",
      columns: [
      { data: 'ticket_id', name: 'ticket_id', className: '' },
      { data: 'subject', name: 'Judul' },
      { data: 'ticket_date', name: 'Tanggal Ticket' },
      { data: 'requester_name', name: 'Requester' },
      {data: 'action', name: 'Action', orderable: false},
      ],
      order: [[0, 'desc']],
    });
   
    table.buttons().container().addClass( 'btn-adjustment' );

    $('body').on('click', '.delete', function () {
      if (confirm("Delete Record?") == true) {
        var id = $(this).data('ticket_id');
        // ajax
        $.ajax({
          type:"POST",
          url: "{{ url('ticket/inactive') }}",
          data: { id: id},
          dataType: 'json',
          success: function(res){
            var oTable = $('#datatable-crud').dataTable();
            oTable.fnDraw(false);
          }
        });
      }
    });
  });
</script>
@endsection
