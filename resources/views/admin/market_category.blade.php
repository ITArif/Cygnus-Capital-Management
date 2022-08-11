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
                <a href="{{route('market_category')}}" class="nav-link { request()->is('market_category') ? 'active' : '' }}">
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
              All Categoryes
            </h3>
            <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_category_modal" data-toggle="modal" data-target=".category_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-categoryes" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>CATEGORY NAME</th>
                  <th>SHARE MATURITY DURATION</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                @foreach($get_record as $data)
                  <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->category_name }}</td>
                    <td>{{ $data->mature_share_duration }} days</td>
                    <td>
                      <a data-id="{{ $data->id }}" data-cat-name="{{ $data->category_name }}" data-share-duration="{{ $data->mature_share_duration }}" href="#" class="edit-category btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                      <button id="{{$data->id}}" class="btn btn-danger btn-xs deleteCategorys"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade category_modal" id="modal-add-category">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <form id="category_form" data-parsley-validate=""  method="post">
                        @csrf
                      <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Category Name</label>
                              <input type="text" class="form-control" id="category_name" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Share Maturity Duration</label>
                              <input type="text" class="form-control" id="mature_share_duration" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="button" id="save-data" class="btn btn-success modal-save" value="Save change"><i class="fa fa-save"></i> Save change</button>
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

            <!-- Edit category modal -->
            <div class="modal fade" id="modal-edit-data">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <div class="row">
                          <div class="col-sm-6">
                            <input type="hidden" id="edit_id" name="">
                            <div class="form-group">
                              <label>Category Name</label>
                              <input type="text" class="form-control" id="edit_category_name" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Share Maturity Duration</label>
                              <input type="text" class="form-control" id="edit_mature_share_duration" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="button" id="save-edit-data" class="btn btn-success modal-save" value="Save change"><i class="fa fa-save"></i> Save change</button>

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

 $(function () {
  $(".open_category_modal").on("click", function(e) {
      e.preventDefault();
      $("#modal-add-category").modal();
    });
    
    $("#save-data").on("click", function(e) {
      e.preventDefault();

      // $("#save-data").attr('disabled', true);
      var category_name = $("#category_name").val();
      var mature_share_duration = $("#mature_share_duration").val();
      if(category_name=="") {
        alert("All fieds are required");
        return;
      }
      if(mature_share_duration=="") {
        alert("All fieds are required");
        return;
      }
      var token = "{{ csrf_token() }}";
      var url_data = "{{ url('market_category') }}";

      $.ajax({
                method: "POST",
                url: url_data,
                data: {
                    _token: token,
                    mature_share_duration: mature_share_duration,
                    category_name: category_name,
                },
                success: function(data) {
                  alert("Category inserted successfully");
                  window.location = document.URL;
                }
      });
    });

    // edit portion
    $(".edit-category").on("click", function(e) {
      e.preventDefault();
      var edit_category_name = $(this).attr('data-cat-name');
      var edit_mature_share_duration = $(this).attr('data-share-duration');
      var edit_id = $(this).attr('data-id');

      $("#edit_id").val(edit_id);
      $("#edit_category_name").val(edit_category_name);
      $("#edit_mature_share_duration").val(edit_mature_share_duration);
      $("#modal-edit-data").modal();
    });

    $("#save-edit-data").on("click", function(e) {
      e.preventDefault();

      // $("#save-data").attr('disabled', true);
      var category_name = $("#edit_category_name").val();
      var mature_share_duration = $("#edit_mature_share_duration").val();
      var edit_id = $("#edit_id").val();

      if(category_name=="") {
        alert("All fieds are required");
        return;
      }
      if(mature_share_duration=="") {
        alert("All fieds are required");
        return;
      }
      var token = "{{ csrf_token() }}";
      var url_data = "{{ url('edit_market_category') }}";

      $.ajax({
                method: "POST",
                url: url_data,
                data: {
                    _token: token,
                    edit_id: edit_id,
                    mature_share_duration: mature_share_duration,
                    category_name: category_name,
                },
                success: function(data) {
                  alert("Category updated successfully");
                  window.location = document.URL;
                }
      });
    });

  $(".deleteCategorys").click(function () {
      var id=$(this).attr('id');
      var url="{{url('delete_market_category')}}";
      $.ajax({
          url:url+"/"+id,
          type:"GET",
          dataType:"json",
          beforeSend:function () {
              Swal.fire({
                  title: 'Deleting the category data.....',
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
                      text: 'You Have Successfully Deleted Category Data',
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
  $('#all-categoryes').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection