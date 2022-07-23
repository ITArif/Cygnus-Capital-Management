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
        <form action="{{route('market_data_news')}}" method="post">
          @csrf
          <div class="row">
            <div class="col-5">
               <input type="text" id="from_date" class="form-control my_datepicker" autocomplete="off" name="from_date" placeholder="From Date">
            </div>
            <div class="col-5">
              <input type="text" id="to_date" class="form-control my_datepicker" autocomplete="off" name="to_date" placeholder="To Date">
            </div>
            <button type="submit" class="btn btn-info">Submit</button>
          </div><br/>
        </form>
          <div class="card card-outline card-primary">
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width: 10px!important;">Trading code</th>
                    <th>News Details</th>
                    <th style="width: 10px!important;">Posted At</th>                      
                  </tr>
                  @foreach($news_data as $data)
                  <tr>
                    <td>{{ $data->MAN_ANNOUNCEMENT_PREFIX }}</td>
                    <td>{{ $data->MAN_ANNOUNCEMENT }}</td>
                    <td>{{ $data->MAN_ANNOUNCEMENT_DATE_TIME }}</td>
                  </tr>
                  @endforeach
               </tbody>
              </table>
            </div>
          </div>
        <div class="card-footer">
          <a href="{{route('add_news_data')}}" class="btn btn-primary">Add More</a>
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
    $( "#from_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  } );
 $( function() {
    $( "#to_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  } );

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