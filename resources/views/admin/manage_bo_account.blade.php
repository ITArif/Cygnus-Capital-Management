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
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              You can search by name, email and client code
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
                  <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" id="name" class="form-control" autocomplete="off" name="name" placeholder="Enter Name">
                      </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <input type="text" id="email" class="form-control" autocomplete="off" name="email" placeholder="Enter Email">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <input type="text" id="client_code" class="form-control" autocomplete="off" name="client_code" placeholder="Enter Client Code">
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
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All B.O Accounts</h3>
          </div>
          <div class="card-body">
            @if(count($get_data) > 1)
            <table class="table table-bordered" id="allData">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Name</th>
                  <th>Email Address</th>
                  <th>Client Code</th>
                  <th>B.O Type</th>
                  <!-- <th>B.O Category</th> -->
                  <th>Client Limit</th>
                  <th>IPO Applied</th>
                  <!-- <th>Name of First Holder</th> -->
                  <th>Sex</th>
                  <th>Created AT</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i= 1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                      <?php
                        /*$user_name = App\User::where('id', $val->user_id)->first();
                        if(count($user_name)) {
                          echo $user_name->name;
                        }*/
                        echo $val->bo_short_name;
                      ?>
                    </td>
                    <td>{{ $val->email_id }}</td>
                    <td>{{ $val->dp_internal_reference_number }}</td>
                    <td>{{ $val->bo_type }}</td>
                    <!-- <td>{{ $val->bo_category }}</td> -->
                    <td>
                      <?php
                        $limit_data = App\Models\ClientLimits::where('clientcode', $val->dp_internal_reference_number)->first();
                        if($limit_data) {
                          echo $limit_data->cash;
                        }
                      ?>
                    </td>
                    <td>{{ $val->ipo_apply }}</td>
                    <!-- <td>{{ $val->name_of_first_holder }}</td> -->
                    <td>{{ $val->sex_code }}</td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                      <!-- <a class="btn btn-success btn-xs edit-user" data-edit_id="{{ $val->id }}">Add User</a> -->
                      <a href="{{ URL::to('edit_bo_account') }}/{{ $val->id }}" class="btn btn-primary btn-xs">Edit</a>
                      <a target="_blank" href="{{ URL::to('view_bo_account') }}/{{ $val->id }}" class="btn btn-success btn-xs">View</a>
                      <a onclick="return confirm('Are you sure to remove this B.O Account');" class="btn btn-danger btn-xs" href="{{ URL::to('delete_bo_account') }}/{{ $val->id }}">Delete</a>
                      <a onclick="return confirm('Are you sure to export this B.O Account');" class="btn btn-warning btn-xs" href="{{ URL::to('export_bo_account') }}/{{ $val->id }}">Export</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <table class="table table-bordered" id="allData">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Name</th>
                  <th>Email Address</th>
                  <th>Client Code</th>
                  <th>B.O Type</th>
                  <!-- <th>B.O Category</th> -->
                  <th>Client Limit</th>
                  <th>IPO Applied</th>
                  <!-- <th>Name of First Holder</th> -->
                  <th>Sex</th>
                  <th>Created AT</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i= 1; ?>
                @foreach($get_data as $val)
                  <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                      <?php
                        /*$user_name = App\User::where('id', $val->user_id)->first();
                        if(count($user_name)) {
                          echo $user_name->name;
                        }*/
                        echo $val->bo_short_name;
                      ?>
                    </td>
                    <td>{{ $val->email_id }}</td>
                    <td>{{ $val->dp_internal_reference_number }}</td>
                    <td>{{ $val->bo_type }}</td>
                    <!-- <td>{{ $val->bo_category }}</td> -->
                    <td>
                      <?php
                        $limit_data = App\Models\ClientLimits::where('clientcode', $val->dp_internal_reference_number)->first();
                        if(is_array($limit_data)) {
                          echo $limit_data->cash;
                        }
                      ?>
                    </td>
                    <td>{{ $val->ipo_apply }}</td>
                    <!-- <td>{{ $val->name_of_first_holder }}</td> -->
                    <td>{{ $val->sex_code }}</td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                      <!-- <a class="btn btn-success btn-xs edit-user" data-edit_id="{{ $val->id }}">Add User</a> -->
                      <a href="{{ URL::to('edit_bo_account') }}/{{ $val->id }}" class="btn btn-primary btn-xs">Edit</a>
                      <a target="_blank" href="{{ URL::to('view_bo_account') }}/{{ $val->id }}" class="btn btn-success btn-xs">View</a>
                      <a onclick="return confirm('Are you sure to remove this B.O Account');" class="btn btn-danger btn-xs" href="{{ URL::to('delete_bo_account') }}/{{ $val->id }}">Delete</a>
                      <a onclick="return confirm('Are you sure to export this B.O Account');" class="btn btn-warning btn-xs" href="{{ URL::to('export_bo_account') }}/{{ $val->id }}">Export</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @endif
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
 $(document).ready(function() {
    $('#allData').DataTable( {
        // scrollY:        '50vh',
        // scrollX:        '50vh',
        scrollCollapse: true,
      //"responsive": true,
      // "autoWidth": false,
    } );
  } );

</script>
@endsection