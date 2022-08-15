@extends('master')

@section('title', 'CCTM | Admin Dashboard')
@section('dashboard-title', 'Dashboard')

@section('stylesheets')
    <!-- <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@8.10.0/dist/sweetalert2.css" rel="stylesheet"> -->
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
      <section class="col-lg-6">
        <div class="card">
          <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            User Summery
            </h3>
            <div class="card-tools">
              <div class="form-group">
                  <select name="project_name" class="form-control select2bs4" id="project_name">
                      <option value="">--select project name--</option>
                      <option value="">fdfdfdf</option>
                  </select>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content p-0">
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="revenue-chart-canvas" height="300" style="height: 300px; display: block; width: 542px;" width="542" class="chartjs-render-monitor"></canvas>
            </div>
            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
            <canvas id="sales-chart-canvas" height="0" style="height: 0px; display: block; width: 0px;" class="chartjs-render-monitor" width="0"></canvas>
            </div>
            </div>
          </div>
        </div>
      </section>
      <section class="col-lg-6">
        <div class="card">
          <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            User Summery
            </h3>
            <div class="card-tools">
              <div class="form-group">
                  <select name="project_name" class="form-control select2bs4" id="project_name">
                      <option value="">--select project name--</option>
                      <option value="">fdfdfdf</option>
                  </select>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content p-0">
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="revenue-chart-canvas" height="300" style="height: 300px; display: block; width: 542px;" width="542" class="chartjs-render-monitor"></canvas>
            </div>
            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
            <canvas id="sales-chart-canvas" height="0" style="height: 0px; display: block; width: 0px;" class="chartjs-render-monitor" width="0"></canvas>
            </div>
            </div>
          </div>
        </div>
      </section>
     </div>
  </div><!-- /.container-fluid -->
</section>
@endsection

@section('custom_script')
<script>
  
</script>

@endsection