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
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">
              All Circuit Breaker Data
            </h3>
            <!-- <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all_stock_order_grid" class="table table-bordered table-striped">
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
                  <th>Status</th>
                  <th>From</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>
                      <?php $x = App\User::where('id', $val->user_id)->first(['name']); echo $x->name; ?>
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
                    <td>
                      <?php if( ($val->order_status==1) || ($val->order_status==2) || ($val->order_status==3) || ($val->order_status==4) ) : ?>
                        <a class="change_stock_status btn btn-primary btn-xs" data-id="{{ $val->id }}" data-order-type="{{ $val->order_type }}" data-status="{{ $val->order_status }}" href="#">Change Status</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php $i++; ?>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade stockModal" id="stockModal">
              <div class="modal-dialog modal-md">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <form data-parsley-validate=""  method="post" action=""> 
                        <div class="row">
                            <div class="col-md-12">
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
                            <div class="modal-footer">
                              <div class="col-sm-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                                <!-- <button type="button" id="save_industry" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button> -->
                                <input type="submit" class="btn btn-primary float-right" id="save-circuit" value="Save change" />
                              </div>
                            </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /end Add user.modal -->

            <!-- Edit user modal -->
            <div class="modal fade edit_industry_modal" id="modal-edit-circuit">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">                    
                    <div class="modal-body">
                      <div class="col-md-12">
                        <!-- text input -->
                          <div class="row">
                            <div class="col-sm-12">
                              <input type="hidden" id="edit_id" name="">
                              <div class="form-group">
                                <label>Range Start</label>
                                <input type="number" class="form-control" id="edit_range_start" required>
                              </div>
                            </div>
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Range end</label>
                                <input type="number" class="form-control year_end" id="edit_range_end" required>
                              </div>
                            </div>
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Circuit Breaker Value (%)</label>
                                <input type="number" class="form-control" id="edit_breaker_value" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <div class="col-sm-12">                       
                                <div class="form-group uftcl-about-submit">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                                  <!-- <button type="submit" id="save-edit-data" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button> -->
                                   <button type="submit" id="stockOrder" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-off"></span> Change</button>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
            <!-- /Edit add user modal -->
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
  } );

$(document).ready(function() {
  $('#all_stock_order_grid').DataTable( {
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

  function load_data() {

      var token = "{{ csrf_token() }}";
      var url_data = "{{ url('all_stock_order_data') }}";
      $.ajax({
        method: "POST",
        url: url_data,
        data: {
            _token: token,
        },
        success: function(data) {
          $("#all_stock_order_grid").html(data);
        }
      });

    /*var time = "{{ time() }}";
    $("#all_stock_order_grid").append("Hello World " + time);*/
  }

</script>
@endsection