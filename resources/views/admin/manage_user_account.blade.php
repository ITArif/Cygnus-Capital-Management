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
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">All User Accounts Data</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered" id="allUserData">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Signature</th>
                  <th>Email</th>
                  <th>Client Code</th>
                  <th>Total B.O Account</th>
                  <!-- <th>User Type</th> -->
                  <th>Mobile</th>
                  <th>Status</th>
                  <th>Created AT</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $val->name }}</td>
                     <td>
                      @if($val->image)
                        <img src="{{ url('/custom_files/user/'.$val->image) }}" width="60">
                      @endif
                    </td>
                    <td>
                      @if($val->signature)
                        <img src="{{ url('/custom_files/signature/'.$val->signature) }}" width="60">
                      @endif
                    </td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->clientcode }}</td>
                    <td>
                      <?php
                        $bo_acc = App\Models\UserBOAccountData::where('user_id', $val->id)->get();
                        if(count($bo_acc)) {
                          echo count($bo_acc);
                        } else {
                          echo 0;
                        }
                      ?>
                    </td>
                    <!-- <td>{{ $val->user_type }}</td> -->
                    <td>{{ $val->mobile }}</td>
                    <td>
                    @if($val->verified == 1)
                      @if($val->password_create_status == 1)
                        <span class="badge bg-success">Active</span>
                      @elseif($val->password_create_status == 0)
                        <span class="badge bg-warning">Password has not created</span>
                      @endif
                    @elseif($val->verified == 0)
                      <span class="badge bg-danger">Inactive</span>
                    @endif
                    </td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                    <a onclick="return confirm('Are you sure to edit this user');" class="btn btn-primary btn-xs" href="{{ URL::to('edit_user') }}/{{ $val->id }}">Edit</a>
                    @if($val->verified == 1)
                      <a onclick="return confirm('Are you sure to ban this user');" class="btn btn-danger btn-xs" href="{{ URL::to('ban_user') }}/{{ $val->id }}">Ban User</a>
                    @else
                      <a onclick="return confirm('Are you sure to active this user');" class="btn btn-success btn-xs" href="{{ URL::to('unban_user') }}/{{ $val->id }}">Unban User</a>
                    @endif
                    <a onclick="return confirm('Are you sure to delete this user');" class="btn btn-warning btn-xs" href="{{ URL::to('delete_user') }}/{{ $val->id }}">Delete</a>
                    <a data-user-id="{{ $val->id }}" class="btn btn-success btn-xs change-pass" href="">Change Password</a>
                    @if($val->verified == 1 and $val->password_create_status == 0)
                    <a data-user-id="{{ $val->id }}" class="btn btn-info btn-xs created-pass" href="{{ URL::to('/send-new-email')}}/{{ $val->id }}">Create Password</a>
                    @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="modal fade" id="passModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <form class="" method="POST" action="{{ url('change_user_pass') }}">
                     @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Change Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="card-body">
                            <div class="form-group">
                              <label for="name"> Change Password</label>
                              <input type="hidden" id="user_id" name="user_id" class="form-control">
                              <input type="password" id="password" name="password" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" id="passChange" class="btn btn-primary btn-block" value="Change Password" name="submit" style="color: #fff">
                        </div>
                    </form>
                    </div>
                </div>
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
  $(".change-pass").on("click", function(e) {
    e.preventDefault();
    var user_id = $(this).attr('data-user-id');
    $("#user_id").val(user_id);
    $("#password").val("");
    $("#passModal").modal();
  });

  $("#add-user").on("click", function(e) {
    e.preventDefault();
    $("#modal-add-user").modal();
  });

  $("#modal-save").on("click", function() {
      var client_code = $("#client_code").val();
      var user_type = $("#user_type").val();
      var name = $("#name").val();
      var joined_at = $("#datepicker").val();
      var email = $("#email").val();
      var mobile = $("#mobile").val();
      var post_id = $("#hidden_post_id").val();
      var token = "{{ csrf_token() }}";
      var url_data = "{{ url('manage_user_account') }}";
      var page_type = "add_user";

        $.ajax({
                  method: "POST",
                  url: url_data,
                  data: {
                      _token: token,
                      client_code: client_code,
                      user_type: user_type,
                      name: name,
                      joined_at: joined_at,
                      email: email,
                      mobile: mobile,
                      page_type: page_type
                  },
                  success: function(data) {
                    alert("User account created successfully");
                    window.location = document.URL;
                  }
        });
  });
 $(document).ready(function() {
    $('#allUserData').DataTable( {
        // scrollY:        '50vh',
        // scrollX:        '50vh',
        scrollCollapse: true,
      //"responsive": true,
      // "autoWidth": false,
    } );
  } );

</script>
@endsection