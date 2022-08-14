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
      <div class="col-md-4">
        <div class="card card-widget widget-user-2">
          <div class="widget-user-header bg-primary">
            <h3 class="widget-user-username">Group Account</h3>
          </div>
          <div class="card-footer p-0">
            <ul class="nav flex-column">
              <li class="nav-item has-treeview">
               <a href="{{route('manage_group_account')}}" class="nav-link">
                Manage Group <span class="float-right badge bg-warning">{{$total_group_data}}</span>
               </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="{{route('create_group_account')}}" class="nav-link">
                Create Group <span class="float-right badge bg-info"></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">
              All Groups
            </h3>
            <div class="btn-group float-right">
              <a href="{{route('create_group_account')}}" class="btn btn-primary float-sm-right"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-groups" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>GROUP NAME </th>
                  <th>B.O IDS</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($get_record as $val)
                  <tr>
                    <td>{{ $val->group_name }}</td>
                      <td>
                        <?php
                          $bo_ids = explode(",", $val->bo_ids);
                          foreach($bo_ids as $bo) {
                            echo $bo . "<br />";
                          }
                        ?>
                      </td>
                    <td>
                      <a class="btn btn-success btn-xs edit-groups" href="{{route('edit_group_account',$val->id)}}"><i class="fas fa-edit"></i></a>
                      <button id="{{$val->id}}" class="btn btn-danger btn-xs deleteGroups"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
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

  $(".deleteGroups").click(function () {
      var id=$(this).attr('id');
      var url="{{url('delete_group_account')}}";
      $.ajax({
          url:url+"/"+id,
          type:"GET",
          dataType:"json",
          beforeSend:function () {
              Swal.fire({
                  title: 'Deleting the group data.....',
                  html:"<i class='fa fa-spinner fa-spin' style='font-size: 24px;'></i>",
                  confirmButtonColor: '#3085d6',
                  allowOutSideClick:false,
                  showCancelButton:false,
                  showConfirmButton:false
              });
          },
          success:function (response) {
              Swal.close();
              if(response==="success") {
                  Swal.fire({
                      title:'success',
                      text: 'You Have Successfully Deleted Group Data',
                      type:'success',
                      confirmButtonText: 'OK'
                  }).then(function(result){
                      if (result.value) {
                          window.location.reload();
                      }
                  });
              }
              console.log(response)
          },
          error:function (error) {
              Swal.fire({
                  title: 'Error',
                  text:'Something Went Wrong',
                  type:'error',
                  showConfirmButton: true
              });
              console.log(error);
          }
      })
  });
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