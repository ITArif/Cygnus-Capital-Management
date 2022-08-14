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
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Update Cash Limit</h3>
            <!-- <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="{{route('update_cash_limit')}}" method="post">
              @csrf
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" id="security_code" class="form-control" name="security_code" required placeholder="Enter Security Code">
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" id="amount" class="form-control" name="amount" required placeholder="Enter Amount">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <!--  <a href="#" class="btn btn-primary float-left">Search</a> -->
                 <!-- <button id="buttonSearch" type="submit" class="btn btn-primary float-right" style="margin-right: 10px">
                  <span class="fas fa-search"></span>&nbsp;Search
                 </button> -->
                 <input type="submit" id="submit_form" class="btn btn-primary float-right" value="Submit" name="submit">
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
    $( "#from_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
    $( "#to_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  } );

</script>
@endsection