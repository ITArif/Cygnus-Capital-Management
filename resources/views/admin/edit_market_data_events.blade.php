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
              <li class="nav-item has-treeview">
                <a href="{{route('market_data_events')}}" class="nav-link {{ request()->is('market_data_events') ? 'active' : '' }}">
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
              Edit Events
            </h3>
          </div>
          <div class="card-body">
            <form data-parsley-validate="" method="post" action="{{route('edit_market_data_events',$events->id)}}">
              @csrf
              <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Category Name</label>
                      <select name="category" id="category" class="form-control select2bs4" required>
                        <option <?php echo ($events->category=="AGM") ? "selected" : ""; ?> value="AGM">AGM</option>
                        <option <?php echo ($events->category=="EGM") ? "selected" : ""; ?> value="EGM">EGM</option>
                        <option <?php echo ($events->category=="Record Date Divident") ? "selected" : ""; ?> value="Record Date Divident">Record Date Divident</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Trading Code</label>
                      <input type="text" class="form-control" name="trading_code" id="trading_code" value="{{$events->trading_code}}">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Year end</label>
                      <input type="text" class="form-control year_end" name="year_end" id="datepicker" value="{{$events->year_end}}"> 
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                       <label>Divident In (%)</label>
                      <input type="text" class="form-control" name="divident_in" id="divident_in" value="{{$events->divident_in}}"> 
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Vanue</label>
                      <input type="text" class="form-control" 
                     name="vanue" id="vanue" value="{{$events->vanue}}">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Time</label>
                      <input type="text" name="time" class="form-control" id="time" value="{{$events->time}}">
                    </div>
                  </div>
              </div>
              <div class="card-footer">
                <input type="submit" name="submit" class="btn btn-primary float-right" value="Save change" />
                <a href="{{route('market_data_events')}}" class="btn btn-default">Cancel</a>
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
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  } );
</script>
@endsection