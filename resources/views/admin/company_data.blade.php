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
              All Company Data
            </h3>
            <div class="btn-group float-right">
              <a href="#" class="btn btn-primary float-sm-right open_company_modal" data-toggle="modal" data-target=".company_modal" style="cursor: pointer"><i class="fas fa-plus"></i>ADD More</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="all-company_data" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>INDUSTRY NAME</th>
                  <th>COMPANY CODE</th>
                  <th>COMPANY NAME</th>
                  <th>CATEGORY</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($get_record as $data)
                  <tr>
                    <td>{{ $data->INDUSTRY_NAME }}</td>
                    <td>{{ $data->COMPANY_CODE }}</td>
                    <td>{{ $data->COMPANY_NAME }}</td>
                    <td>{{ $data->CATEGORY }}</td>
                    <td>
                      <button id="{{$data->ID}}" data-target=".edit_company_modal"data-toggle="modal" data-company="{{$data->id}}" data-industry_name="{{$data->INDUSTRY_NAME }}" data-company_code="{{$data->COMPANY_CODE }}" data-company_name="{{$data->COMPANY_NAME }}" data-category="{{ $data->CATEGORY  }}" class="btn btn-info btn-xs edit_company"><i class="fa fa-edit"></i></button>
                      <button id="{{$data->ID}}" class="btn btn-danger btn-xs deleteCompanyData"><i class="far fa-trash-alt"></i></button>
                    </td>
                  </tr>
                @endforeach
             </tbody>
            </table>
            <!-- Add user modal -->
            <div class="modal fade company_modal" id="modal-add-company">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <form id="company_form" data-parsley-validate=""  method="post">
                        @csrf
                      <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Industry Name</label>
                              <select class="form-control select2bs4" id="industry_name" name="industry_name">
                                @foreach($ind_data as $ind)
                                  <option value="{{ $ind->industry_name }}">{{ $ind->industry_name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Category Name</label>
                              <select class="form-control select2bs4" id="category" name="category">
                                @foreach($cat_data as $cat)
                                  <option value="{{ $cat->category_name }}">{{ $cat->category_name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Company Code</label>
                              <input type="text" class="form-control" id="company_code" name="company_code" required> 
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Company Name</label>
                              <input type="text" class="form-control" id="company_name" name="company_name" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="button" id="save_company_data" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button>
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

            <!-- Edit Company Data modal -->
            <div class="modal fade edit_company_modal" id="modal-edit-company">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                  <div class="modal-body">
                    <div class="col-md-12">
                      <form id="edit_company_form" data-parsley-validate=""  method="post">
                        @csrf
                      <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Industry Name</label>
                              <select class="form-control select2bs4" id="edit_industry_name" name="industry_name">
                                <option value="">--select industry name--</option>
                                @foreach($get_record as $industryData)
                                  <option value="{{$industryData->INDUSTRY_NAME}}">{{ $industryData->INDUSTRY_NAME}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Category Name</label>
                              <select class="form-control select2bs4" id="edit_category" name="category">
                                <option value="">--select category name--</option>
                                @foreach($get_record as $category)
                                  <option value="{{ $category->CATEGORY}}">{{ $category->CATEGORY}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Company Code</label>
                              <input type="text" class="form-control" id="edit_company_code" name="company_code" value="{{ $industryData->COMPANY_CODE}}"> 
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Company Name</label>
                              <input type="text" class="form-control" id="edit_company_name" name="company_name" value="{{ $industryData->COMPANY_NAME}}">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" id="save_edit_company_data" class="btn btn-success modal-save"><i class="fa fa-save"></i> Save change</button>
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
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
  });

 $(function () {
  $(".open_company_modal").click(function () {
      var url="{{url('create_company_data')}}";
      $("#company_form").attr('action',url);
  });

  $("#save_company_data").click(function () {
    Swal.fire({
        title: 'Are you sure?',
        text: "Are you want to save the company data!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Save it!'
    }).then(function(result){
        if (result.value) {
            $('#company_form').submit();
        }
    });
  });

  $('.edit_company').click(function () {
      var id = $(this).attr('id');
      //alert(id);
      var industry_name=$(this).attr('data-industry_name');
      var category=$(this).attr('data-category');
      var company_code=$(this).attr('data-company_code');
      var company_name=$(this).attr('data-company_name');
      //$("#edit_id").val(edit_id);
      $('#edit_industry_name').val(industry_name);
      $('#edit_company_code').val(company_code);
      $('#edit_category').val(category);
      $('#edit_company_name').val(company_name);
      $('#edit_company_form').attr('action','{{ url('edit_company_data/') }}'+'/'+id);
  });

  $(".deleteCompanyData").click(function () {
      var id=$(this).attr('id');
      //alert(id);
      var url="{{url('delete_company_data')}}";
      $.ajax({
          url:url+"/"+id,
          type:"GET",
          dataType:"json",
          beforeSend:function () {
              Swal.fire({
                  title: 'Deleting the company data.....',
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
                      text: 'You Have Successfully Deleted Company Data',
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
  $('#all-company_data').DataTable( {
      "info": true,
        "autoWidth": false,
        scrollX:'50vh',
        scrollY:'50vh',
      scrollCollapse: true,
  } );
} );
</script>
@endsection