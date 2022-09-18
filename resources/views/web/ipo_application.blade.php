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
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              All IPO Application Request
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form data-parsley-validate="" method="post" action="{{route('ipo_application')}}">
              @csrf
              <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <select class="form-control select2" name="script_id" id="script_id">
                       <option>---Select One---</option>
                          @foreach($data as $val)
                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
               </div>
              </div>
              <div class="card-footer">
                <input type="submit" name="submit" class="btn btn-primary float-right" id="submit_form" value="Submit" />
                <a href="#" class="btn btn-default">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @if($get_data)
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              All IPO Application Request
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-groups" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Client Code</th>
                  <th>Script Name</th>
                  <th>Client Balance</th>
                  <th>IPO Amount</th>
                  <th>Amount After Application</th>
                  <th>Request Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $val->client_code }}</td>
                    <td>{{ $val->script_name }}</td>
                    <td>{{ $val->client_balance }}</td>
                    <td>{{ $val->ipo_amount }}</td>
                    <td>{{ $val->amount_after_application }}</td>
                    <td>{{ date("jS F Y", strtotime($val->created_at)) }}</td>
                    <td>
                      @if($val->status == 0)
                        <label class="label label-primary">Pending</label>
                      @elseif($val->status == 1)
                        <label class="label label-success">Accepted</label>
                      @elseif($val->status == 2)
                        <label class="label label-danger">Rejected</label>
                      @endif
                    </td>
                    <td><a class="change_stock_status btn btn-primary btn-xs" data-id="{{ $val->id }}" data-status="{{ $val->status }}" href="#">Change Status</a></td>
                  </tr>
                  <?php $i++; ?>
                @endforeach
             </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @endif
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
    $('.select2').select2();
    $( "#start_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
    // $( "#end_date" ).datepicker({
    //   changeMonth: true,
    //   changeYear: true
    // });
    // $('.select2bs4').select2({
    //   theme: 'bootstrap4'
    // });
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

  // $(".deleteGroups").click(function () {
  //     var id=$(this).attr('id');
  //     var url="{{url('delete_group_account')}}";
  //     $.ajax({
  //         url:url+"/"+id,
  //         type:"GET",
  //         dataType:"json",
  //         beforeSend:function () {
  //             Swal.fire({
  //                 title: 'Deleting the group data.....',
  //                 html:"<i class='fa fa-spinner fa-spin' style='font-size: 24px;'></i>",
  //                 confirmButtonColor: '#3085d6',
  //                 allowOutSideClick:false,
  //                 showCancelButton:false,
  //                 showConfirmButton:false
  //             });
  //         },
  //         success:function (response) {
  //             Swal.close();
  //             if(response==="success") {
  //                 Swal.fire({
  //                     title:'success',
  //                     text: 'You Have Successfully Deleted Group Data',
  //                     type:'success',
  //                     confirmButtonText: 'OK'
  //                 }).then(function(result){
  //                     if (result.value) {
  //                         window.location.reload();
  //                     }
  //                 });
  //             }
  //             console.log(response)
  //         },
  //         error:function (error) {
  //             Swal.fire({
  //                 title: 'Error',
  //                 text:'Something Went Wrong',
  //                 type:'error',
  //                 showConfirmButton: true
  //             });
  //             console.log(error);
  //         }
  //     })
  // });
});

$(document).ready(function() {
  $('#all-groups').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection