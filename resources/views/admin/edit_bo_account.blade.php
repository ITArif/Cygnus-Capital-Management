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
    <div class="card card-default">
      <div class="card-header">
         <h3 class="card-title">Select2 (Default Theme)</h3>
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
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Minimal</label>
              <select class="form-control">
                <option>Alabama</option>
                <option data-select2-id="45">Alaska</option>
                <option data-select2-id="46">California</option>
                <option data-select2-id="47">Delaware</option>
                <option data-select2-id="48">Tennessee</option>
                <option data-select2-id="49">Texas</option>
                <option data-select2-id="50">Washington</option>
              </select>
            </div>
            <div class="form-group">
              <label>Minimal</label>
              <select class="form-control">
                <option>Alabama</option>
                <option data-select2-id="45">Alaska</option>
                <option data-select2-id="46">California</option>
                <option data-select2-id="47">Delaware</option>
                <option data-select2-id="48">Tennessee</option>
                <option data-select2-id="49">Texas</option>
                <option data-select2-id="50">Washington</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Minimal</label>
              <select class="form-control">
                <option>Alabama</option>
                <option data-select2-id="45">Alaska</option>
                <option data-select2-id="46">California</option>
                <option data-select2-id="47">Delaware</option>
                <option data-select2-id="48">Tennessee</option>
                <option data-select2-id="49">Texas</option>
                <option data-select2-id="50">Washington</option>
              </select>
            </div>
            <div class="form-group">
              <label>Minimal</label>
              <select class="form-control">
                <option>Alabama</option>
                <option data-select2-id="45">Alaska</option>
                <option data-select2-id="46">California</option>
                <option data-select2-id="47">Delaware</option>
                <option data-select2-id="48">Tennessee</option>
                <option data-select2-id="49">Texas</option>
                <option data-select2-id="50">Washington</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
      Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
      the plugin.
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