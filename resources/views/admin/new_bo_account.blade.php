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
    <!-- Main row -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-none">
          <span class="info-box-icon bg-info"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">TOTAL USER</span>
            <span class="info-box-number">{{ $all_data->TOT_USER }}</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
          <span class="info-box-icon bg-success"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">FREE USER</span>
            <span class="info-box-number">{{ $all_data->FREE_USER }}</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow">
          <span class="info-box-icon bg-warning"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">PRIMIUM USER</span>
            <span class="info-box-number">{{ $all_data->PREMIUM_USER }}</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-lg">
          <span class="info-box-icon bg-danger"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">ADMIN USER</span>
            <span class="info-box-number">{{ $all_data->ADMIN_USER }}</span>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">All B.O Account Data</h3>
            <!-- <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="bo-request" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>User Name</th>
                  <th>Email Address</th>
                  <th>Client Code</th>
                  <th>B.O Type</th>
                  <!-- <th>B.O Category</th> -->
                  <th>Client Limit</th>
                  <!-- <th>Name of First Holder</th> -->
                  <th>Sex</th>
                  <th>Created AT</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>
                      <?php
                        $user_name = App\Models\User::where('id', $val->user_id)->first();
                        if(is_array($user_name)) {
                          echo $user_name->name;
                        }
                      ?>
                    </td>
                    <td>{{ $val->email_id }}</td>
                    <td>{{ $val->dp_internal_reference_number }}</td>
                    <td>{{ $val->bo_type }}</td>
                    <!-- <td>{{ $val->bo_category }}</td> -->
                    <td>
                      <?php
                        $limit_data = App\Models\ClientLimits::where('clientcode', $val->dp_internal_reference_number)->first();
                        if(is_array($limit_data)) {
                          echo $limit_data->cash;
                        }
                      ?>
                    </td>
                    <!-- <td>{{ $val->name_of_first_holder }}</td> -->
                    <td>{{ $val->sex_code }}</td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                      <a class="btn btn-success btn-xs edit-user" data-edit_id="{{ $val->id }}">Add User</a>
                      <a href="{{route('edit_bo_account',$val->id)}}" class="btn btn-primary btn-xs">Edit</a>
                      <a onclick="return confirm('Are you sure to remove this B.O Account');" class="btn btn-danger btn-xs" href="{{ route('delete_bo_account',$val->id) }}">Delete</a>
                      <a target="_blank" href="{{ route('view_bo_account',$val->id) }}" class="btn btn-success btn-xs">View</a>
                      <a onclick="return confirm('Are you sure to export this B.O Account');" class="btn btn-warning btn-xs" href="{{route('export_bo_account',$val->id) }}">Export</a>
                    </td>
                  </tr>
                  <?php $i++; ?>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="modal fade" id="modal-papana">
            <div class="modal-dialog modal-md">
              <div class="modal-content">                    
                <div class="modal-body">
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-12">
                            <h2>User Add to B.O Account</h2><br />
                          <div class="form-group uftcl-about">
                            <label>User Name</label>
                            <select id="user_id" class="form-control select2bs4">
                              <option value="">-- Select User --</option>
                              @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                              @endforeach
                            </select>
                          </div>
                          </div>
                          <div class="modal-footer">
                            <div class="col-sm-12">
                              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                              <!-- <button type="button" id="save_industry" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button> -->
                              <input type="submit" class="btn btn-primary float-right" id="modal-save" value="Save change" />
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
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
@endsection

@section('custom_script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('assets/plugins/jquery-ui/datepicker.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.11.1/dist/sweetalert2.js"></script>
<script>
$( function() {
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  });
var edit_id = "";
$(".edit-user").on("click", function(e) {
  edit_id = $(this).data('edit_id');
  e.preventDefault();
  $("#modal-papana").modal();
});

$("#modal-save").on("click", function() {
    var user_id = $("#user_id").val();
    var token = "{{ csrf_token() }}";
    var url = "{{ url('manage_bo_account') }}";
    var page_type = "add_bo_user";
    $("#modal-papana").modal();

    $.ajax({
      method: "POST",
      url: url,
      data: {
        _token: token,
        account_id: edit_id,
        user_id: user_id,
        page_type: page_type
      },
      success: function(data) {
        alert("User added successfully");
        window.location = document.URL;
      }
    });
});
$("#upload-file").on("click", function() {
  var upload_url = "{{ url('upload_bo_account') }}";
  window.location = upload_url;
});

$(document).ready(function() {
  $('#bo-request').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>

@endsection