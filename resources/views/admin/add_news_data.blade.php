@extends('master')
@section('title', 'CCTM | Admin Dashboard')
@section('dashboard-title', 'Dashboard')

@section('stylesheets')
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.11.5/dist/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" 
     href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                Events <span class="float-right badge bg-info">5</span>
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
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add News Data</h3>
          </div>
          <div class="card-body">
            <form action="{{route('add_news_data')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Trading Code</label>
                    <input type="text" class="form-control" name="trading_code">
                    @if($errors->has('trading_code'))
                      <span class="text-danger">{{ $errors->first('trading_code') }}</span>
                    @endif
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>News Title</label>
                    <input type="text" class="form-control" name="news_title">
                    @if($errors->has('news_title'))
                    <span class="text-danger">{{ $errors->first('news_title') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>News Description</label>
                    <textarea class="form-control" name="news_description" rows="3"></textarea>
                    @if($errors->has('news_description'))
                    <span class="text-danger">{{ $errors->first('news_description') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                   <label>Company Logo</label>
                    <input type="file" id="upload_file" name="upload_file" class="form-control">
                    @if($errors->has('upload_file'))
                    <span class="text-danger">{{ $errors->first('upload_file') }}</span>
                    @endif
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Company Logo Preview </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <img id="logo_preview" src="" style="width: 250px;height: 110px">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
               <input type="reset" class="btn btn-default" value="Cancel" />
               <input type="submit" name="submit" class="btn btn-primary float-right" value="Save change" />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
function readURLImg(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
          $('#logo_preview').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
  }
}
$("#upload_file").change(function() {
    readURLImg(this);
});

@if(Session::has('success'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.success("{{ session('success') }}");
  @endif

  @if(Session::has('error'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options =
  {
    "closeButton" : true,
    "progressBar" : true
  }
      toastr.warning("{{ session('warning') }}");
  @endif
</script>
@endsection