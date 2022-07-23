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
            <h3 class="widget-user-username">Market Data</h3>
          </div>
          <div class="card-footer p-0">
            <ul class="nav flex-column">
              <li class="nav-item has-treeview">
               <a href="{{route('market_data_news')}}" class="nav-link {{ request()->is('market_data_news') ? 'active' : '' }}">
                News <span class="float-right badge bg-warning">{{$total_news_data}}</span>
               </a>
              </li>
              <li class="nav-item">
                <a href="{{route('market_data_events')}}" class="nav-link">
                Events <span class="float-right badge bg-info">{{$total_events_data}}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Category <span class="float-right badge bg-success">12</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Industry Data <span class="float-right badge bg-danger">842</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Company Data <span class="float-right badge bg-danger">842</span>
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
              All Events
            </h3>
            <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_event_modal" data-toggle="modal" data-target=".event_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-events" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>CATEGORY</th>
                  <th>TRADING CODE</th>
                  <th>YEAR END</th>
                  <th>DIVIDENT IN (%)</th>
                  <th>VANUE</th>
                  <th>TIME</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($events as $event)
                  <tr>
                    <td>{{ $event->category }}</td>
                    <td>{{ $event->trading_code }}</td>
                    <td>{{ $event->year_end }}</td>
                    <td>{{ $event->divident_in }}</td>
                    <td>{{ $event->vanue }}</td>
                    <td>{{ $event->time }}</td>
                    <td>
                      <a class="btn btn-success btn-xs edit-user" data-edit_id="{{ $event->id }}"><i class="fas fa-edit"></i></a>
                      <button id="{{$event->id}}" class="btn btn-danger btn-xs deleteEvents"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade event_modal" id="modal-add-event">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <form id="event_form" data-parsley-validate=""  method="post">
                        @csrf
                      <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Category Name</label>
                              <select name="category" id="category" class="form-control" required>
                                <option value="AGM">AGM</option>
                                <option value="EGM">EGM</option>
                                <option value="Record Date Divident">Record Date Divident</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Trading Code</label>
                              <input type="text" class="form-control" name="trading_code" id="trading_code" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Year end</label>
                              <input type="text" class="form-control year_end" name="datepicker" id="datepicker" required> 
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                               <label>Divident In (%)</label>
                              <input type="text" class="form-control" name="divident_in" id="divident_in" required> 
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Vanue</label>
                              <input type="text" class="form-control" 
                             name="vanue" id="vanue" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Time</label>
                              <input type="text" name="time" class="form-control" id="time" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="button" id="save_event" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button>
                          </div>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
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
  } );

 $(function () {
  $(".open_event_modal").click(function () {
      var url="{{url('create_market_data_events')}}";
      $("#event_form").attr('action',url);
  });

  $("#save_event").click(function () {
    Swal.fire({
        title: 'Are you sure?',
        text: "Are you want to save the events!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Save it!'
    }).then(function(result){
        if (result.value) {
            $('#event_form').submit();
        }
    });
  });

  $(".deleteEvents").click(function () {
      var id=$(this).attr('id');
      var url="{{url('delete_events')}}";
      $.ajax({
          url:url+"/"+id,
          type:"GET",
          dataType:"json",
          beforeSend:function () {
              Swal.fire({
                  title: 'Deleting the event data.....',
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
                      text: 'You Have Successfully Deleted Event',
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
  $('#all-events').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection