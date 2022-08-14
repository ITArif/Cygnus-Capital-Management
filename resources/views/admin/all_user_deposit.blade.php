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
                <input type="submit" id="submit_form" class="btn btn-success" value="Submit" name="submit">
                <input type="submit" class="btn btn-warning" value="Export to Excel" name="submit" onclick="exportToExcel('tblId','deposit')" style="margin-left: 10px;">
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
            <h3 class="card-title">All Deposit Request</h3>
            <!-- <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_circuit_breker_modal" data-toggle="modal" data-target=".circuit_breker_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="deposit-request" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Client Code</th>
                  <th>Bank Name</th>
                  <th>Deposit Date</th>
                  <th>Deposit Amount</th>
                  <th>Request Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $val->client_code }}</td>
                    <td>{{ $val->bank_name }}</td>
                    <td>{{ $val->deposit_date }}</td>
                    <td>{{ $val->deposit_amount }}</td>
                    <td>{{ date("jS F Y", strtotime($val->created_at)) }}</td>
                    <td>
                      @if($val->status == 1)
                        <label class="label label-danger">Pending</label>
                      @elseif($val->status == 2)
                        <label class="label label-primary">Submitted</label>
                      @elseif($val->status == 3)
                        <label class="label label-warning">Rejected</label>
                      @elseif($val->status == 4)
                        <label class="label label-warning">Cancel</label>
                      @elseif($val->status == 5)
                        <label class="label label-success">Executed</label>
                      @elseif($val->status == 6)
                        <button type="button" class="btn btn-block btn-danger btn-xs">Canceled By User</button>
                      @endif
                    </td>
                    <td>
                      <a class="change_stock_status btn btn-primary btn-xs" data-id="{{ $val->id }}" data-status="{{ $val->status }}" href="#">Change Status</a>
                    </td>
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
  $('#deposit-request').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );

$(document).on("click", ".change_stock_status", function(e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  var order_status = $(this).attr('data-status');

  $("#stock_id").val(id);
  $("#order_status option[value='"+order_status+"']").attr('selected', 'selected');
  $("#stockModal").modal();
});

$(document).on("click", "#stockOrder", function(e) {
  e.preventDefault();
  var stock_id = $("#stock_id").val();
  var order_status = $("#order_status").val();
  var token = "{{ csrf_token() }}";
  var url_data = "{{ url('all_user_deposit') }}";
  // var url_data = "{{ url('all_user_withdrawal') }}";
    
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
          order_status: order_status
      },
      success: function(data) {
        alert(data);
        window.location = document.URL;
      }
    });
});

</script>
<script>
function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}


function exportToExcel(tableID, filename){
  var downloadLink;
  var dataType = 'application/vnd.ms-excel';
  var tableSelect = document.getElementById(tableID);
  var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
  
  // Specify file name
  filename = filename?filename+'.xls':'excel_data.xls';
  
  // Create download link element
  downloadLink = document.createElement("a");
  
  document.body.appendChild(downloadLink);
  
  if(navigator.msSaveOrOpenBlob){
      var blob = new Blob(['\ufeff', tableHTML], {
          type: dataType
      });
      navigator.msSaveOrOpenBlob( blob, filename);
  }else{
      // Create a link to the file
      downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
  
      // Setting the file name
      downloadLink.download = filename;
      
      //triggering the function
      downloadLink.click();
  }
}
</script>
@endsection