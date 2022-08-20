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
    <div class="card card-primary">
      <div class="card-header">
         <h3 class="card-title">Edit B.O Account Form</h3>
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
        <form action="{{route('edit_bo_account',$val->id)}}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>B.O Identification Number</label>
                <input type="text" class="form-control" name="bo_identification_number" value="{{ $val->bo_identification_number }}" required>
              </div>
              <div class="form-group">
                <label>B.O Type</label>
                <input type="text" class="form-control" name="bo_type" value="{{ $val->bo_type }}" required>
              </div>
              <div class="form-group">
                <label>B.O Category</label>
                <input type="text" class="form-control" name="bo_category" value="{{ $val->bo_category }}" required>
              </div>
              <div class="form-group">
                <label>DP Internal Reference Number</label>
                <input type="text" class="form-control" name="dp_internal_reference_number" value="{{ $val->dp_internal_reference_number }}">
              </div>
              <div class="form-group">
                <label>Name of  First Holder</label>
                <input type="text" class="form-control" name="name_of_first_holder" value="{{ $val->name_of_first_holder }}">
              </div>
              <div class="form-group">
                <label>Second Joint Holder</label>
                <input type="text" class="form-control" name="second_joint_holder" value="{{ $val->second_joint_holder }}">
              </div>
              <div class="form-group">
                <label>Third Joint Holder</label>
                <input type="text" class="form-control" name="third_joint_holder" value="{{ $val->third_joint_holder }}">
              </div>
              <div class="form-group">
                <label>Contact Person Name</label>
                <input type="text" class="form-control" name="contact_person_name" value="{{ $val->contact_person_name }}">
              </div>
              <div class="form-group">
                <label>Sex Code</label>
                <input type="text" class="form-control" name="sex_code" value="{{ $val->sex_code }}" required>
              </div>
              <div class="form-group">
                <label>Date of Birth</label>
                <input type="text" class="form-control my_datepicker" name="date_of_birth" value="{{ $val->date_of_birth }}" required>
              </div>
              <div class="form-group">
                <label>Registration Number</label>
                <input type="text" class="form-control" name="registration_number" value="{{ $val->registration_number }}">
              </div>
              <div class="form-group">
                <label>Father or Husband Name</label>
                <input type="text" class="form-control" name="father_or_husband_name" value="{{ $val->father_or_husband_name }}" required>
              </div>
              <div class="form-group">
                <label>Mother Name</label>
                <input type="text" class="form-control" name="mother_name" value="{{ $val->mother_name }}" required>
              </div>
              <div class="form-group">
                <label>Occupation</label>
                <input type="text" class="form-control" name="occupation" value="{{ $val->occupation }}">
              </div>
              <div class="form-group">
                <label>Residency Flag</label>
                <input type="text" class="form-control" name="residency_flag" value="{{ $val->residency_flag }}">
              </div>
              <div class="form-group">
                <label>Nationality</label>
                <input type="text" class="form-control" name="nationality" value="{{ $val->nationality }}" required>
              </div>
              <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address_1" value="{{ $val->address_1 }}">
              </div>
              <div class="form-group">
                <label>Address (Two)</label>
                <input type="text" class="form-control" name="address_2" value="{{ $val->address_2 }}">
              </div>
              <div class="form-group">
                <label>Address (Three)</label>
                <input type="text" class="form-control" name="address_3" value="{{ $val->address_3 }}">
              </div>
              <div class="form-group">
                <label>City</label>
                <input type="text" class="form-control" name="city" value="{{ $val->city }}" required>
              </div>
              <div class="form-group">
                <label>State</label>
                <input type="text" class="form-control" name="state" value="{{ $val->state }}" required>
              </div>
              <div class="form-group">
                <label>Country</label>
                <input type="text" class="form-control" name="country" value="{{ $val->country }}" required>
              </div>
              <div class="form-group">
                <label>Postal Code</label>
                <input type="number" class="form-control" name="postal_code" value="{{ $val->postal_code }}">
              </div>
              <div class="form-group">
                <label>Phone Number</label>
                <input type="text" class="form-control" name="phone_number" value="{{ $val->phone_number }}">
              </div>
              <div class="form-group">
                <label>Email ID</label>
                <input type="email" class="form-control" name="email_id" value="{{ $val->email_id }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Fax Number</label>
                <input type="text" class="form-control" name="fax_number" value="{{ $val->fax_number }}">
              </div>
              <div class="form-group">
                <label>Statement Cycle Code</label>
                <input type="text" class="form-control" name="statement_cycle_code" value="{{ $val->statement_cycle_code }}">
              </div>
              <div class="form-group">
                <label>B.O Short Name</label>
                <input type="text" class="form-control" name="bo_short_name" value="{{ $val->bo_short_name }}" required>
              </div>
              <div class="form-group">
                <label>Second Holder Short Name</label>
                <input type="text" class="form-control" name="second_holder_short_name" value="{{ $val->second_holder_short_name }}">
              </div>
              <div class="form-group">
                <label>Third Holder Short Name</label>
                <input type="text" class="form-control" name="third_holder_short_name" value="{{ $val->third_holder_short_name }}">
              </div>
              <div class="form-group">
                <label>Passport Number</label>
                <input type="text" class="form-control" name="passport_number" value="{{ $val->passport_number }}">
              </div>
              <div class="form-group">
                <label>Passport Issue Date</label>
                <input type="text" class="form-control my_datepicker" id="passport_issue_date" name="passport_issue_date" value="{{ $val->passport_issue_date }}">
              </div>
              <div class="form-group">
                <label>Passport Expiry Date</label>
                <input type="text" class="form-control my_datepicker" id="passport_expiry_date" name="passport_expiry_date" value="{{ $val->passport_expiry_date }}">
              </div>
              <div class="form-group">
                <label>Passport Issue Place</label>
                <input type="text" class="form-control" name="passport_issue_place" value="{{ $val->passport_issue_place }}">
              </div>
              <div class="form-group">
                <label>Bank Name</label>
                <input type="text" class="form-control" name="bank_name" value="{{ $val->bank_name }}">
              </div>
              <div class="form-group">
                <label>Bank Branch Name</label>
                <input type="text" class="form-control" name="bank_branch_name" value="{{ $val->bank_branch_name }}">
              </div>
              <div class="form-group">
                <label>Bank Account Number</label>
                <input type="text" class="form-control" name="bank_account_number" value="{{ $val->bank_account_number }}" required>
              </div>
              <div class="form-group">
                <label>Electronic Dividend Flag</label>
                <input type="text" class="form-control" name="electronic_dividend_flag" value="{{ $val->electronic_dividend_flag }}">
              </div>
              <div class="form-group">
                <label>Tax Exemption Flag</label>
                <input type="text" class="form-control" name="tax_exemption_flag" value="{{ $val->tax_exemption_flag }}">
              </div>
              <div class="form-group">
                <label>Tax Identification Number</label>
                <input type="text" class="form-control" name="tax_identification_number" value="{{ $val->tax_identification_number }}">
              </div>
              <div class="form-group">
                <label>Exchange ID</label>
                <input type="text" class="form-control" name="exchange_id" value="{{ $val->exchange_id }}">
              </div>
              <div class="form-group">
                <label>Trading ID</label>
                <input type="text" class="form-control" name="trading_id" value="{{ $val->trading_id }}">
              </div>
              <div class="form-group">
                <label>Bank Routine Number</label>
                <input type="text" class="form-control" name="bank_routine_number" value="{{ $val->bank_routine_number }}">
              </div>
              <div class="form-group">
                <label>Bank Identification Code</label>
                <input type="text" class="form-control" name="bank_identification_code" value="{{ $val->bank_identification_code }}">
              </div>
              <div class="form-group">
                <label>International Bank Account Number</label>
                <input type="text" class="form-control" name="international_bank_account_number" value="{{ $val->international_bank_account_number }}">
              </div>
              <div class="form-group">
                <label>Bank Swift Code</label>
                <input type="text" class="form-control" name="bank_swift_code" value="{{ $val->bank_swift_code }}">
              </div>
              <div class="form-group">
                <label>First Holder National ID</label>
                <input type="text" class="form-control" name="first_holder_national_id" value="{{ $val->first_holder_national_id }}">
              </div>
              <div class="form-group">
                <label>Second Holder National ID</label>
                <input type="text" class="form-control" name="second_holder_national_id" value="{{ $val->bo_second_holder_national_idtype }}">
              </div>
              <div class="form-group">
                <label>Third Holder National ID</label>
                <input type="text" class="form-control" name="third_holder_national_id" value="{{ $val->third_holder_national_id }}">
              </div>
            </div>
          </div>
          <input type="submit" name="submit" class="btn btn-primary float-right" value="Save Account" />
        </form>
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
  $( "#passport_issue_date" ).datepicker({
    changeMonth: true,
    changeYear: true
  });
  $( "#passport_expiry_date" ).datepicker({
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