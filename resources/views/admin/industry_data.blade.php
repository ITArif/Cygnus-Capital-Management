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
                <a href="{{route('market_category')}}" class="nav-link {{ request()->is('market_category') ? 'active' : '' }}">
                Category <span class="float-right badge bg-success">{{$total_category_data}}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('industry_data')}}" class="nav-link {{ request()->is('industry_data') ? 'active' : '' }}">
                Industry Data <span class="float-right badge bg-danger">{{$total_industry_data}}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('company_data')}}" class="nav-link {{ request()->is('company_data') ? 'active' : '' }}">
                Company Data <span class="float-right badge bg-danger">{{$total_industryData}}</span>
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
              All Industry Data
            </h3>
            <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_industry_modal" data-toggle="modal" data-target=".industry_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-industry-data" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>INDUSTRY NAME </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($get_record as $data)
                  <tr>
                    <td>{{ $data->industry_name }}</td>
                    <td>
                      <button id="{{$data->id}}" data-target=".edit_industry_modal" data-toggle="modal" data-industry="{{$data->id}}" data-industry_name="{{$data->industry_name }}" class="btn btn-info btn-xs edit_industry"><i class="fa fa-edit"></i></button>

                      <button id="{{$data->id}}" class="btn btn-danger btn-xs deleteIndustryData"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade industry_modal" id="modal-add-industry">
              <div class="modal-dialog modal-md">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <form id="industry_form" data-parsley-validate=""  method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Industry Name</label>
                                 <input type="text" class="form-control" id="industry_name" name="industry_name" required>
                              </div>
                            </div><br>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                              <button type="button" id="save_industry" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button>
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
            <div class="modal fade edit_industry_modal" id="modal-edit-data">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">                    
                    <div class="modal-body">
                      <div class="col-md-12">
                        <form id="edit_industry_form" data-parsley-validate=""  action="" method="post">
                          @csrf
                        <!-- text input -->
                          <div class="row">
                            <div class="form-group uftcl-about">
                              <label>Industry Name</label>
                              <input type="text" class="form-control" id="edit_industry_name" name="industry_name" required>
                            </div>
                            <div class="modal-footer">
                              <div class="col-sm-12">                       
                                <div class="form-group uftcl-about-submit">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                                  <button type="submit" id="save-edit-data" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button>
                                </div>
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

 $(function () {
  $(".open_industry_modal").click(function () {
      var url="{{url('create_industry_data')}}";
      $("#industry_form").attr('action',url);
  });

  $("#save_industry").click(function () {
    Swal.fire({
        title: 'Are you sure?',
        text: "Are you want to save the industry Data!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Save it!'
    }).then(function(result){
        if (result.value) {
            $('#industry_form').submit();
        }
    });
  });

  $('.edit_industry').click(function () {
      var id=$(this).attr('id');
      var industry_name=$(this).attr('data-industry_name');
      $('#edit_industry_name').val(industry_name);
      $('#edit_industry_form').attr('action','{{ url('edit_industry_data/') }}'+'/'+id);
  });

  $(".deleteIndustryData").click(function () {
      var id=$(this).attr('id');
      var url="{{url('delete_industry_data')}}";
      $.ajax({
          url:url+"/"+id,
          type:"GET",
          dataType:"json",
          beforeSend:function () {
              Swal.fire({
                  title: 'Deleting the industry data.....',
                  html:"<i class='fa fa-spinner fa-spin' style='font-size: 24px;'></i>",
                  confirmButtonColor: '#3085d6',
                  allowOutSideClick:false,
                  showCancelButton:false,
                  showConfirmButton:false
              });
          },
          success:function (response) {
              Swal.close();
              if(response==="success") {
                  Swal.fire({
                      title:'success',
                      text: 'You Have Successfully Deleted The Industry Data',
                      type:'success',
                      confirmButtonText: 'OK'
                  }).then(function(result){
                      if (result.value) {
                          window.location.reload();
                      }
                  });
              }
              console.log(response)
          },
          error:function (error) {
              Swal.fire({
                  title: 'Error',
                  text:'Something Went Wrong',
                  type:'error',
                  showConfirmButton: true
              });
              console.log(error);
          }
      })
  });
});

$(document).ready(function() {
  $('#all-industry-data').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection