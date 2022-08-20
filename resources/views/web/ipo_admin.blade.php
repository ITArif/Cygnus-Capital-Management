@extends('master')
@section('title', 'CCTM | Admin Dashboard')
@section('dashboard-title', 'Dashboard')

@section('stylesheets')
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.11.5/dist/sweetalert2.min.css">
@endsection

@section('container')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">
              All IPO Settings
            </h3>
            <div class="btn-group float-right">
              <a href="{{route('ipo_setting')}}" class="btn btn-primary float-sm-right"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-ipos" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Script Name</th>
                  <th>Amount</th>
                  <th>Application Start Date</th>
                  <th>Application End Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->amount }}</td>
                    <td>{{ date("jS F Y", strtotime($val->start_date)) }}</td>
                    <td>{{ date("jS F Y", strtotime($val->end_date)) }}</td>
                    <td>
                       @if($val->status == 1)
                        <a href="{{ URL::to('/ipo_status_change/'.$val->id) }}" class="btn btn-success btn-xs">Active</a>
                       @else
                        <a href="{{ URL::to('/ipo_status_change/'.$val->id) }}" class="btn btn-danger btn-xs">Inactive</a>
                       @endif
                    </td>
                  </tr>
                  <?php $i++; ?>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            
            <!-- /end Add user.modal -->

            <!-- Edit user modal -->
            
            <!-- /Edit add user modal -->
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('custom_script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('assets/plugins/jquery-ui/datepicker.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.11.1/dist/sweetalert2.js"></script>
<script>
  /* date pickter */
 $( function() {
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  });

 $(function () {
  // $(".open_event_modal").click(function () {
  //     var url="{{url('create_market_data_events')}}";
  //     $("#event_form").attr('action',url);
  // });

  // $("#save_event").click(function () {
  //   Swal.fire({
  //       title: 'Are you sure?',
  //       text: "Are you want to save the events!",
  //       type: 'warning',
  //       showCancelButton: true,
  //       confirmButtonColor: '#3085d6',
  //       cancelButtonColor: '#d33',
  //       confirmButtonText: 'Yes, Save it!'
  //   }).then(function(result){
  //       if (result.value) {
  //           $('#event_form').submit();
  //       }
  //   });
  // });
});

$(document).ready(function() {
  $('#all-ipos').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection