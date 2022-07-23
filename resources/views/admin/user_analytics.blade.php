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
            <span class="info-box-number">150</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
          <span class="info-box-icon bg-success"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">FREE USER</span>
            <span class="info-box-number">500</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow">
          <span class="info-box-icon bg-warning"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">PRIMIUM USER</span>
            <span class="info-box-number">400</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-lg">
          <span class="info-box-icon bg-danger"><i class="ion ion-person-add"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">ADMIN USER</span>
            <span class="info-box-number">200</span>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">
              All Users
            </h3>
            <div class="btn-group float-right">
              <span class="btn btn-primary float-sm-right" id="add-user"><i class="fa fa-plus"></i> Add User</span>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-users" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Name</th>
                  <th>User Type</th>
                  <th>Email Address</th>
                  <th>Contact Number</th>
                  <th>Joined AT</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $val->name }}</td>
                    <td>Admin User</td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->mobile }}</td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                      <a class="btn btn-success btn-xs edit-user" data-edit_id="{{ $val->id }}"><i class="fas fa-edit"></i></a>
                      <button id="{{$val->id}}" class="btn btn-danger btn-xs deleteUser"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
                  <?php $i++; ?>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade" id="modal-add-user">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-sm-6">
                          &nbsp;<br><br>
                          <div class="form-group uftcl-about">
                            <label>User Name</label>
                            <input type="text" class="form-control" id="user_id">
                          </div>
                          <div class="form-group uftcl-about">
                            <label>Full Name</label>
                            <input type="text" id="name" class="form-control" required>
                          </div>
                          <div class="form-group uftcl-about">
                            <label>Email Address</label>
                            <input type="text" id="email" class="form-control" required>
                          </div>
                          <div class="form-group uftcl-about">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                          </div>
                          <!-- select -->
                          <div class="form-group uftcl-about" style="display:none;">
                            <label>user type</label>
                            <select class="form-control" id="user_type">
                              <option value="Free">Free User</option>
                              <option value="Premium">Premium User</option>
                            </select>
                          </div>
                          <!-- Date -->
                          <div class="form-group uftcl-about">
                            <label>Joined Date</label>                    
                            <input type="text" class="form-control" id="datepicker" autocomplete="off">
                          </div>
                          <!-- select -->
                          <div class="form-group uftcl-about">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" id="mobile">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="button" id="modal-save" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button>
                          </div>
                         </div>
                        <div class="col-sm-6">
                          &nbsp;<br><br>
                          <div class="form-group uftcl-about">
                            <label>User Access</label>                          
                          </div>
                          <ul>
                            @foreach($permissions as $permission)
                              <li>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="permission" value="{{ $permission->permission_name }}">
                                    {{ ucwords(str_replace("_", " ", $permission->permission_name)) }}
                                  </label>
                                </div>
                              </li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /end Add user.modal -->

            <!-- Edit user modal -->
            <div class="modal fade" id="modal-papana">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-sm-6">
                          &nbsp;<br><br>
                          <input id="edit_papana_id" type="hidden" name="edit_papana_id">
                          <div class="form-group uftcl-about">
                            <label>User Name</label>
                            <input type="text" class="form-control" id="edit_user_id">
                          </div>
                          <div class="form-group uftcl-about">
                            <label>name</label>
                            <input type="text" id="edit_name" class="form-control">
                          </div>
                          <div class="form-group uftcl-about">
                            <label>email address</label>
                            <input type="text" id="edit_email" class="form-control">
                          </div> 
                          <!-- Date -->
                          <div class="form-group uftcl-about">
                            <label>joined Date</label>                           
                            <input type="text" class="form-control edit_joined_date" id="datepicker2">
                          </div>
                          <!-- select -->
                          <div class="form-group uftcl-about">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" id="edit_mobile">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="button" id="modal-papana-save" class="btn btn-success modal-papana-save"><i class="fa fa-save"></i> Save change</button>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          &nbsp;<br><br>
                          <div class="form-group uftcl-about">
                            <label>User Access</label>                          
                          </div>
                          <ul>
                            @foreach($permissions as $permission)
                              <li>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" class="permission" name="edit_permission" value="{{ $permission->permission_name }}">
                                    {{ ucwords(str_replace("_", " ", $permission->permission_name)) }}
                                  </label>
                                </div>
                              </li>
                            @endforeach
                          </ul>
                        </div>
                        <div class="col-sm-12 uftcl-edit-analitics">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
            </div>
            <!-- /Edit add user modal -->
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
$("#add-user").on("click", function(e) {
  e.preventDefault();
  $("#modal-add-user").modal();
  /* date pickter */
 $( function() {
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  } );
 $( function() {
    $( "#datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  } );
});
//delete User
$('.deleteUser').click(function () {
     var id = $(this).attr('id');
     Swal.fire({
         title: 'Are you sure?',
         text: "You won't be able to revert this!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, delete it!'
     }).then(function(result){
         if (result.value) {
             // delet by ajax
             var url = "{{url('delete-user-analytics')}}";
             $.ajax({
                 /*config part*/
                 url:url+"/"+id,
                 type:"GET",
                 dataType:"json",
                 /*Config part*/
                 beforeSend:function () {
                     Swal.fire({
                         title: 'The User Data.......',
                         html:"<i class='fa fa-spinner fa-spin' style='font-size: 24px'></i>",
                         allowOutsideClick:false,
                         showCancelButton: false,
                         showConfirmButton: false
                     });
                 },
                 success:function (response) {
                     Swal.close();
                     if(response==="success"){
                         Swal.fire({
                             title: 'Success',
                             text: "You Have Successfully Deleted The User",
                             type: 'success',
                             confirmButtonText: 'OK'
                         }).then(function(result){
                             if (result.value) {
                                 window.location.reload();
                             }
                         });
                     }
                     console.log(response);
                 },
                 error:function (error) {
                     Swal.fire({
                         title: 'Error',
                         text:'Something Went Wrong',
                         type:'error',
                         showConfirmButton: true
                     });
                     console.log(error)
                 }
             })
         }
     });
 });
$("#modal-save").on("click", function() {
    var user_id = $("#user_id").val();
    var user_type = $("#user_type").val();
    var name = $("#name").val();
    var joined_at = $("#datepicker").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var mobile = $("#mobile").val();
    var post_id = $("#hidden_post_id").val();
    var token = "{{ csrf_token() }}";
    var url_data = "{{ url('user_analytics') }}";
    var page_type = "add_user";
    var permissions = [];
    $('input[name=permission]:checked').each(function(i) {
      permissions[i] = $(this).val();
    });

    // validation start
    if(user_id=="" || name=="" || email=="" || password=="") {
      alert("User name, name, email & password are required");
      return;
    }
    if(permissions.length == 0) {
      alert("You must select at least one permission");
      return;
    }
    $.ajax({
      method: "POST",
      url: url_data,
      data: {
          _token: token,
          user_id: user_id,
          user_type: user_type,
          name: name,
          joined_at: joined_at,
          email: email,
          password: password,
          mobile: mobile,
          page_type: page_type,
          permissions: permissions
      },
      success: function(data) {
        alert("User created successfully");
        window.location = document.URL;
      }
    });
});


// code edit user
$(".edit-user").on("click", function(e) {
  e.preventDefault();
    var edit_id = $(this).data('edit_id');
    var token = "{{ csrf_token() }}";
    var edit_url = "{{ url('user_analytics') }}";
    var page_type = "edit_user_get";
    $("#modal-papana").modal();

    $.ajax({
      method: "POST",
      url: edit_url,
      data: {
        _token: token,
        user_id: edit_id,
        page_type: page_type
      },
      success: function(data) {
        console.log(data);
        var new_data = JSON.parse(data);
        console.log(new_data.permissions);
        $("#edit_name").val(new_data.name);
        $("#edit_user_id").val(new_data.user_id);
        $(".edit_joined_date").val(new_data.joined_date);
        $("#edit_email").val(new_data.email);
        $("#edit_mobile").val(new_data.mobile);
        $("#edit_papana_id").val(edit_id);



      }
    });
});

$("#modal-papana-save").on("click", function() {
    var user_id = $("#edit_user_id").val();
    // var user_type = $("#user_type").val();
    var name = $("#edit_name").val();
    var joined_at = $("#datepicker2").val();
    var email = $("#edit_email").val();
    // var password = $("#password").val();
    var mobile = $("#edit_mobile").val();
    var post_id = $("#edit_papana_id").val();
    var token = "{{ csrf_token() }}";
    var url_data = "{{ url('user_analytics') }}";
    var page_type = "edit_user";
    var permissions = [];
    $('input[name=edit_permission]:checked').each(function(i) {
      permissions[i] = $(this).val();
    });



    // validation start
    if(user_id=="" || name=="" || email=="") {
      alert("User name, name & email are required");
      return;
    }
    if(permissions.length == 0) {
      alert("You must select at least one permission");
      return;
    }

    /*alert(user_id);
    alert(name);
    alert(joined_at);
    alert(email);
    alert(mobile);
    alert(post_id);
    alert(url_data);
    return;*/

      $.ajax({
        method: "POST",
        url: url_data,
        data: {
            _token: token,
            user_id: user_id,
            name: name,
            joined_at: joined_at,
            email: email,
            mobile: mobile,
            page_type: page_type,
            permissions: permissions,
            post_id: post_id
        },
        success: function(data) {
          alert("User updated successfully");
          window.location = document.URL;
        }
      });
});
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
$(document).ready(function() {
  $('#all-users').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection