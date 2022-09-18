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
    <div class="card card-primary">
      <div class="card-header">
         <h3 class="card-title">Edit User Account Form</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <form action="{{route('edit_user',$get_data->id)}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Name</label>
               <input type="text" class="form-control" name="name" value="{{ $get_data->name }}" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="{{ $get_data->email }}" disabled>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Client Code</label>
                <input type="text" class="form-control" name="client_code" value="{{ $get_data->client_code }}" required>
              </div>
              <div class="form-group">
                <label>Mobile</label>
                <input type="text" class="form-control" name="mobile" value="{{ $get_data->mobile }}">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>User Type</label>
                <!-- <input type="text" class="form-control" name="mobile" value="{{ $get_data->mobile }}"> -->
                <select name="user_type" class="form-control select2bs4">
                  <option <?php echo ($get_data->user_type == "Free") ? "selected" : ""; ?> value="Free">Free</option>
                  <option <?php echo ($get_data->user_type == "Premium") ? "selected" : ""; ?> value="Premium">Premium</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputFile">Profile Picture</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="upload_file" id="profile_pics">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                   </div>
                  <div class="input-group-append">
                     <span class="input-group-text">Upload</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Profile Image Priview</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <img id="profile_image_preview" style="width: 160px;height: 130px; margin-top:10px" src="{{ url('/custom_files/user/'.$get_data->image) }}" alt="">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Signature</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="upload_file2" id="signature_pic">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                   </div>
                  <div class="input-group-append">
                     <span class="input-group-text">Upload</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Signature Image Priview</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <img id="signature_image_preview" style="width: 160px;height: 130px; margin-top:10px" src="{{ url('/custom_files/signature/'.$get_data->signature) }}" alt="">
                </div>
              </div>
            </div>
          </div>
          <input type="submit" name="submit" class="btn btn-primary float-right" value="Update Profile" />
        </form>
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

$(function () {
  bsCustomFileInput.init();
});
$( function() {
  // $( "#passport_issue_date" ).datepicker({
  //   changeMonth: true,
  //   changeYear: true
  // });
  // $( "#passport_expiry_date" ).datepicker({
  //   changeMonth: true,
  //   changeYear: true
  // });
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#profile_image_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#profile_pics").change(function() {
  readURL(this);
});


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#signature_image_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#signature_pic").change(function() {
  readURL(this);
});
</script>

@endsection