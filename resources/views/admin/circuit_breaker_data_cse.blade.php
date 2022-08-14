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
            <h3 class="widget-user-username">Circuit Breaker</h3>
          </div>
          <div class="card-footer p-0">
            <ul class="nav flex-column">
              <li class="nav-item has-treeview">
               <a href="{{route('circuit_breaker_data')}}" class="nav-link {{ request()->is('circuit_breaker_data') ? 'active' : '' }}">
                DSE <span class="float-right badge bg-warning">{{$total_dse_data}}</span>
               </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="{{route('circuit_breaker_data_cse')}}" class="nav-link {{ request()->is('circuit_breaker_data_cse') ? 'active' : '' }}">
                CSE <span class="float-right badge bg-info">{{$total_cse_data}}</span>
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
              All Circuit Breaker Data
            </h3>
            <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-circuit-data" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>CIRCUIT BREAKER RANGE</th>
                  <th>CIRCUIT BREAKER VALUE </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cse_data as $cse)
                  <tr>
                    <td>{{ $cse->range_start }} - {{ $cse->range_end }}</td>
                      <td>{{ $cse->breaker_value }} %</td>
                    <td>
                      <a data-id="{{ $cse->id }}" data-range-start="{{ $cse->range_start }}" data-range-end="{{ $cse->range_end }}" data-breaker-value="{{ $cse->breaker_value }}" href="#" class="edit-circuit-breaker btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>

                      <button id="{{$cse->id}}" class="btn btn-danger btn-xs deleteCircuitBrekerCSEData"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade circuit_modal" id="modal-add-circuit">
              <div class="modal-dialog modal-md">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Range Start</label>
                                <input type="number" class="form-control" id="range_start" required>
                              </div>
                            </div>
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Range end</label>
                                <input type="number" class="form-control year_end" id="range_end" required>
                              </div>
                            </div>
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Circuit Breaker Value (%)</label>
                                <input type="text" class="form-control" id="breaker_value" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <div class="col-sm-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                                <!-- <button type="button" id="save_industry" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button> -->
                                <input type="submit" class="btn btn-primary float-right" id="save-circuit" value="Save change" />
                              </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /end Add user.modal -->

            <!-- Edit user modal -->
            <div class="modal fade edit_industry_modal" id="modal-edit-circuit">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">                    
                    <div class="modal-body">
                      <div class="col-md-12">
                        <!-- text input -->
                          <div class="row">
                            <div class="col-sm-12">
                              <input type="hidden" id="edit_id" name="">
                              <div class="form-group">
                                <label>Range Start</label>
                                <input type="number" class="form-control" id="edit_range_start" required>
                              </div>
                            </div>
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Range end</label>
                                <input type="number" class="form-control year_end" id="edit_range_end" required>
                              </div>
                            </div>
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Circuit Breaker Value (%)</label>
                                <input type="number" class="form-control" id="edit_breaker_value" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <div class="col-sm-12">                       
                                <div class="form-group uftcl-about-submit">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                                  <!-- <button type="submit" id="save-edit-data" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button> -->
                                   <input type="submit" class="btn btn-primary" id="save-edit-circuit" value="Save change" />  
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
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

 $(".open_circuit_breker_modal").on("click", function(e) {
      e.preventDefault();
      $("#modal-add-circuit").modal();
    });
    $("#save-circuit").on("click", function(e) {
      e.preventDefault();
      var range_start = $("#range_start").val();
      range_start = parseInt(range_start);
      var range_end = $("#range_end").val();
      range_end = parseInt(range_end);
      var breaker_value = parseFloat($("#breaker_value").val());
      var token = "{{ csrf_token() }}";
      var url_data = "{{ url('circuit_breaker_data_cse') }}";

      if((range_start=="" )|| (range_end=="") || (breaker_value=="")) {
        alert("All fields are required");
        return;
      }

      if(range_start > range_end) {
        alert("Range Start must be lower than Range End");
        return;
      }

      $("#save-circuit").attr('disabled', true);
      $.ajax({
        method: "POST",
        url: url_data,
        data: {
            _token: token,
            range_start: range_start,
            range_end: range_end,
            breaker_value: breaker_value,
            page_type: "add"
        },
        success: function(data) {
          alert("Circuit breaker data inserted successfully");
          window.location = document.URL;
        }
      });
    });

    // edit portion
    $(".edit-circuit-breaker").on("click", function(e) {
      e.preventDefault();

      var edit_range_start = $(this).attr('data-range-start');
      var edit_range_end = $(this).attr('data-range-end');
      var edit_breaker_value = $(this).attr('data-breaker-value');
      var edit_id = $(this).attr('data-id');

      $("#edit_id").val(edit_id);
      $("#edit_range_start").val(edit_range_start);
      $("#edit_range_end").val(edit_range_end);
      $("#edit_breaker_value").val(edit_breaker_value);

      $("#modal-edit-circuit").modal();
    });

    $("#save-edit-circuit").on("click", function(e) {
      e.preventDefault();
      var range_start = $("#edit_range_start").val();
      range_start = parseInt(range_start);
      var range_end = $("#edit_range_end").val();
      range_end = parseInt(range_end);
      var breaker_value = $("#edit_breaker_value").val();
      // breaker_value = parseInt(breaker_value);
      var edit_id = $("#edit_id").val();
      var token = "{{ csrf_token() }}";
      var url_data = "{{ url('circuit_breaker_data_cse') }}";

      if((range_start=="" )|| (range_end=="") || (breaker_value=="")) {
        alert("All fields are required");
        return;
      }

      if(range_start > range_end) {
        alert("Range Start must be lower than Range End");
        return;
      }

      // $("#save-edit-event").attr('disabled', true);
      $.ajax({
        method: "POST",
        url: url_data,
        data: {
            _token: token,
            edit_id: edit_id,
            range_start: range_start,
            range_end: range_end,
            breaker_value: breaker_value,
            page_type: "edit"
        },
        success: function(data) {
          alert("Circuit breaker data updated successfully");
          window.location = document.URL;
        }
      });
    });

    $(".deleteCircuitBrekerCSEData").click(function () {
      var id=$(this).attr('id');
      //alert(id);
      var url="{{url('delete_circuit_breaker_data_cse')}}";
      $.ajax({
          url:url+"/"+id,
          type:"GET",
          dataType:"json",
          beforeSend:function () {
              Swal.fire({
                  title: 'Deleting the circuit data.....',
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
                      text: 'You Have Successfully Deleted The Circuit Data',
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

$(document).ready(function() {
  $('#all-circuit-data').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection