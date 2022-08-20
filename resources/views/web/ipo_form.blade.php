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
          <!-- <div class="card-header">
            <h3 class="card-title">
              All Groups
            </h3>
            <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_group_modal" data-toggle="modal" data-target=".group_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div> -->
          <!-- /.card-header -->
          <div class="card-body">
            <form data-parsley-validate="" method="post" action="{{route('save_ipo_data')}}" enctype="multipart/form-data">
              @csrf
              <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Script Name</label>
                      <input type="text" class="form-control" name="name" value="" required>
                      <!-- @if ($errors->has('group_name'))
                          <span class="help-block fred">
                              {{ $errors->first('group_name') }}
                          </span>
                      @endif -->
                    </div>
                    <div class="form-group">
                      <label>Start Date</label>
                      <input type="text" class="form-control my_datepicker" id="start_date" name="start_date">
                      <!-- @if ($errors->has('group_name'))
                          <span class="help-block fred">
                              {{ $errors->first('group_name') }}
                          </span>
                      @endif -->
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Amount</label>
                      <input type="number" class="form-control" name="amount" value="" required id="amount">
                      <!-- @if ($errors->has('bo_ids'))
                          <span class="help-block fred">
                              {{ $errors->first('bo_ids') }}
                          </span>
                      @endif -->
                    </div>
                    <div class="form-group">
                      <label>End Date</label>
                      <input type="text" class="form-control my_datepicker" id="end_date" name="end_date">
                      <!-- @if ($errors->has('bo_ids'))
                          <span class="help-block fred">
                              {{ $errors->first('bo_ids') }}
                          </span>
                      @endif -->
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                     <label for="status" class="form-check-label"> Status</label>
                      <input style="height: auto!important;" type="checkbox" id="status" name="status" value="1">
                      <label>Active</label>
                    </div>
                  </div>
               </div>
              </div>
              <div class="card-footer">
                <input type="submit" name="submit" class="btn btn-primary float-right" value="Save change" />
                <a href="{{route('IPO')}}" class="btn btn-default">Cancel</a>
              </div>
            </form>
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
    $('.select2').select2();
    $( "#start_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
    $( "#end_date" ).datepicker({
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