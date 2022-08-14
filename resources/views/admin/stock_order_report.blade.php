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
            <h3 class="card-title">
              Select From Date And To Date To Continue
            </h3>
            <!-- <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="#" method="post">
              @csrf
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control my_datepicker" autocomplete="off" name="from_date" id="from_date" placeholder="From date" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" class="form-control my_datepicker" autocomplete="off" name="to_date" id="to_date" placeholder="To date" required>
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
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">All Stock Report</h3>
            <!-- <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="stock-oder-report" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>User Name</th>
                  <th>Order Type</th>
                  <th>Market</th>
                  <th>Secutiry Code</th>
                  <th>Client Code</th>
                  <th>Current Rate</th>
                  <th>Order Rate</th>
                  <th>B.O Account</th>
                  <th>Number of Share</th>
                  <th>Total Amount</th>
                  <th>Request Date</th>
                  <th>From</th>
                  <th>Status</th>
                  <!-- <th>Action</th> -->
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>
                      {{$val->user_name}}
                    </td>
                    <td>
                      @if($val->order_type == "buy")
                        <label class="label label-success">{{ $val->order_type }}</label>
                      @elseif($val->order_type == "sell")
                        <label class="label label-warning">{{ $val->order_type }}</label>
                      @endif
                    </td>
                    <td>{{ $val->market_type }}</td>
                    <td>{{ $val->security_code }}</td>
                    <td>{{ $val->client_code }}</td>
                    <td>{{ $val->current_rate }}</td>
                    <td>{{ $val->order_rate }}</td>
                    <td>{{ $val->bo_account }}</td>
                    <td>{{ $val->number_of_share }}</td>
                    <td>{{ $val->number_of_share*$val->order_rate }}</td>
                    <td>{{ date("jS F Y", strtotime($val->created_at)) }}</td>
                    <td>
                      @if($val->order_status == 1)
                        <label class="label label-danger">Pending</label>
                      @elseif($val->order_status == 2)
                        <label class="label label-primary">Submitted</label>
                      @elseif($val->order_status == 3)
                        <label class="label label-warning">Rejected</label>
                      @elseif($val->order_status == 4)
                        <label class="label label-warning">Cancel</label>
                      @elseif($val->order_status == 5)
                        <label class="label label-success">Executed</label>
                      @elseif($val->order_status == 6)
                        <label class="label label-danger">Canceled By User</label>
                      @endif
                    </td>
                    <td>
                      @if($val->flag == 'WEB')
                        <label class="label label-success">WEB</label>
                      @elseif($val->flag == 'APP')
                        <label class="label label-primary">APP</label>
                      @endif
                    </td>
                    <!-- <td>
                      <?php //if( ($val->order_status==1) || ($val->order_status==2) || ($val->order_status==3) || ($val->order_status==4) ) : ?>
                        <a class="change_stock_status btn btn-primary btn-xs" data-id="{{ $val->id }}" data-order-type="{{ $val->order_type }}" data-status="{{ $val->order_status }}" href="#">Change Status</a>
                      <?php //endif; ?>
                    </td> -->
                  </tr>
                  <?php $i++; ?>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="stockModal" role="dialog">
  <div class="modal-dialog" style="width:400px !important">
    <!-- Modal content-->
    <div class="modal-content">
      <!-- <div class="modal-header" style="padding:15px 50px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div> -->
      <div class="modal-body" style="padding:40px 50px;">
        <form class="" method="POST" action="">
          @csrf
          <div class="form-group">
            <label for="name"> Change Status</label>
            <input type="hidden" id="stock_id" name="stock_id">
            <input type="hidden" id="order_type" name="order_type">
            <select class="form-control" id="order_status">
              <option value="1">Pending</option>
              <option value="2">Submitted</option>
              <option value="3">Rejected</option>
              <option value="4">Cancel</option>
              <option value="5">Executed</option>
            </select>
          </div>
            <button type="submit" id="stockOrder" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-off"></span> Change</button>
        </form>
      </div>
      <div class="modal-footer">
        &nbsp;
      </div>
    </div>
  </div>
  </div>
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

$(document).ready(function() {
  $('#stock-oder-report').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
  setInterval(load_data, 5000);
} );

$(document).on("click", ".change_stock_status", function(e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  var order_status = $(this).attr('data-status');
  var order_type = $(this).attr('data-order-type');

  $("#stock_id").val(id);
  $("#order_type").val(order_type);
  $("#order_status option[value='"+order_status+"']").attr('selected', 'selected');
  $("#stockModal").modal();

});

$(document).on("click", "#stockOrder", function(e) {
  e.preventDefault();
  var stock_id = $("#stock_id").val();
  var order_type = $("#order_type").val();
  var order_status = $("#order_status").val();
  var token = "{{ csrf_token() }}";
  var url_data = "{{ url('all_stock_order') }}";
    
  if(stock_id=="" || order_status=="") {
    alert("All fields are required");
    return;
  }

    $.ajax({
      method: "POST",
      url: url_data,
      data: {
          _token: token,
          stock_id: stock_id,
          order_type: order_type,
          order_status: order_status
      },
      success: function(data) {
        alert(data);
        window.location = document.URL;
      }
    });

});

</script>
@endsection