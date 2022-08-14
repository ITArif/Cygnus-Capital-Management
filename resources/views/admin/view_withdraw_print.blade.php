<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Requisition Slip</title>
  <!-- <link rel="stylesheet"  type="text/css"  href="print.css" media="print" /> -->

  <!-- <style type="text/css" media="all"> @import "print.css";</style> -->
  <!-- <link rel="stylesheet" type="text/css" media="all" href="print.css" /> -->
  <style>

/* requiaition page start */


.lik-uftcl-rqsn-main-body{
  width: 800px;
  height: auto;
  margin: 0 auto;
  padding: 0;
/*  float: left; */
  
}
.lik-uftcl-rqsn-print-body{
  width: 800px;
  height: 1126px;
  margin: 0 auto;
  padding: 0;
  overflow: hidden;
  clear: both;
  
}

.rqsn-body-top,
.rqsn-body-mid,
.rqsn-header-part {
    width: 800px;
    height: auto;
    margin: 0 ;
    padding: 0 ;
    float: left;
    
}


.rqsn-body-top,
.rqsn-body-mid{   
    border:1px solid #999;
    width: 788px;
    padding: 4px;
}
.rqsn-body-top,
.rqsn-body-mid{margin-bottom: 10px;} 
  
  .rqsn-right-header,
  .rqsn-left-header {    
      height: auto;
      margin: 0 6px 0 0;
      padding: 0;
      float: left;
  }
  .rqsn-left-header { width: 180px;}
  .rqsn-right-header{ width: 610px;margin-right: 0;}

  
  

    .rqsn-right-header p,
    .rqsn-left-header p{      
        margin: 0 0 2px;
        padding: 0;
        font-size: 11px;
        font-family: 'helvetica';
    }
    .rqsn-left-header p{text-align: left;}
    .rqsn-right-header p{text-align: right;}
    .rqsn-right-header p:first-child{
      font-weight: 600;
      font-size: 12px;
    }

    .rqsn-ln-1,   .rqsn-ln-2,   .rqsn-ln-3,   .rqsn-ln-4,   .rqsn-ln-5,
    .rqsn-ln-6,   .rqsn-ln-7,   .rqsn-ln-8,   .rqsn-ln-9,   .rqsn-ln-10,  
    .rqsn-ln-11,  .rqsn-ln-12,  .rqsn-ln-13,  .rqsn-ln-14,  .rqsn-ln-15,  
    .rqsn-ln-16,  .rqsn-ln-17,  .rqsn-ln-18,  .rqsn-ln-19,  .rqsn-ln-20,  
    .rqsn-ln-21,  .rqsn-ln-22,  .rqsn-ln-23,  .rqsn-ln-24,  .rqsn-ln-25,  
    .rqsn-ln-26,  .rqsn-ln-27,  .rqsn-ln-28,  .rqsn-ln-29,  .rqsn-ln-30,  
    .rqsn-ln-31,  .rqsn-ln-32,  .rqsn-ln-33,  .rqsn-ln-34,  .rqsn-ln-35,  
    .rqsn-ln-36,  .rqsn-ln-37,  .rqsn-ln-38,  .rqsn-ln-39,  .rqsn-ln-40{
      padding: 0;
      margin: 0 0 5px;
      font-size: 12px;
      font-family: 'helvetica';
      float: left;
      width: 100%;
    }


    
    /* page 14 */
    .rqsn-ln-1 span,    .rqsn-ln-2 span,    .rqsn-ln-3 span,    .rqsn-ln-4 span,    .rqsn-ln-5 span,
    .rqsn-ln-6 span,    .rqsn-ln-7 span,    .rqsn-ln-8 span,    .rqsn-ln-9 span,    .rqsn-ln-10 span, 
    .rqsn-ln-11 span,   .rqsn-ln-12 span,   .rqsn-ln-13 span,   .rqsn-ln-14 span,   .rqsn-ln-15 span, 
    .rqsn-ln-16 span,   .rqsn-ln-17 span,   .rqsn-ln-18 span,   .rqsn-ln-19 span,   .rqsn-ln-20 span,
    .rqsn-ln-21 span{
      float: left;
      margin: 0;
      padding: 0;
      line-height: 25px;
      margin-right: 5px;
    }
    /* page 14 */
    .rqsn-ln-1 span input[type="checkbox"],     .rqsn-ln-2 span input[type="checkbox"],     .rqsn-ln-3 span input[type="checkbox"],     .rqsn-ln-4 span input[type="checkbox"],     .rqsn-ln-5 span input[type="checkbox"],
    .rqsn-ln-6 span input[type="checkbox"],     .rqsn-ln-7 span input[type="checkbox"],     .rqsn-ln-8 span input[type="checkbox"],     .rqsn-ln-9 span input[type="checkbox"],     .rqsn-ln-10 span input[type="checkbox"],  
    .rqsn-ln-11 span input[type="checkbox"],    .rqsn-ln-12 span input[type="checkbox"],    .rqsn-ln-13 span input[type="checkbox"],    .rqsn-ln-14 span input[type="checkbox"],    .rqsn-ln-15 span input[type="checkbox"],  
    .rqsn-ln-16 span input[type="checkbox"],    .rqsn-ln-17 span input[type="checkbox"],    .rqsn-ln-18 span input[type="checkbox"],    .rqsn-ln-19 span input[type="checkbox"],    .rqsn-ln-20 span input[type="checkbox"],
    .rqsn-ln-21 span input[type="checkbox"]{
      width: 25px;  
      height: 25px; 
      float: right;
      margin: 0 5px 0 0;
    }
    /* page 14 */
    .rqsn-ln-1 span input[type="text"],     .rqsn-ln-2 span input[type="text"],     .rqsn-ln-3 span input[type="text"],     .rqsn-ln-4 span input[type="text"],     .rqsn-ln-5 span input[type="text"],
    .rqsn-ln-6 span input[type="text"],     .rqsn-ln-7 span input[type="text"],     .rqsn-ln-8 span input[type="text"],     .rqsn-ln-9 span input[type="text"],     .rqsn-ln-10 span input[type="text"],  
    .rqsn-ln-11 span input[type="text"],    .rqsn-ln-12 span input[type="text"],    .rqsn-ln-13 span input[type="text"],    .rqsn-ln-14 span input[type="text"],    .rqsn-ln-15 span input[type="text"],  
    .rqsn-ln-16 span input[type="text"],    .rqsn-ln-17 span input[type="text"],    .rqsn-ln-18 span input[type="text"],    .rqsn-ln-19 span input[type="text"],    .rqsn-ln-20 span input[type="text"],
    .rqsn-ln-21 span input[type="text"],    .rqsn-ln-22 span input[type="text"]{
      height: 20px; 
      border: none; 
      border-bottom: 1px dashed #333; 
      float: right; 
      
    }


    .rqsn-ln-1{
      text-align: right;
        font-size: 16px;        
        padding: 0;
        margin: 0;
    }
      .rqsn-ln-1 span{
        text-align: left;
          font-size: 16px;          
          padding: 5px;
          display: block;
          width: 140px;
          background-color: #000;
          color: #fff;
          margin: 0;
          float: right;
          text-transform: uppercase;
          
          
      }
    
      .rqsn-ln-2 span{
        text-align: left;
          font-size: 15px;          
          padding: 0;
          display: block;
          margin: 0;
          float: left;
          font-weight: 600;
          
      }
      .rqsn-ln-2 span:nth-child(1){width: 30px;}
      .rqsn-ln-2 span:nth-child(2){width: 740px;text-align: center;margin-right: 0;}

      .rqsn-ln-3 span:nth-child(1){
        width: 535px;
        font-size: 15px;
        font-weight: 600;
      }
      .rqsn-ln-3 span:nth-child(2){width: 245px;margin-right: 0;}
      .rqsn-ln-3 span:nth-child(2) input{width: 205px;}


      .rqsn-ln-4 span:nth-child(1){width: 210px;}
      .rqsn-ln-4 span:nth-child(1) input{
        width: 20px;
        border: 1px solid #999;
        border-right:none;
      }
      .rqsn-ln-4 span:nth-child(1) input:first-child{ border-right: 1px solid #999;}

      .rqsn-ln-4 span:nth-child(2){width: 570px;margin-right: 0;}
      .rqsn-ln-4 span:nth-child(2) input{width: 505px;border: 1px solid #999;}


      .rqsn-ln-5 span{width: 785px;margin-right: 0;}
      .rqsn-ln-5 span input{width: 710px;margin-right: 0;}

      .rqsn-ln-6 span:nth-child(1){width: 430px;}
      .rqsn-ln-6 span:nth-child(1) input{width: 315px;margin-right: 0;}
      .rqsn-ln-6 span:nth-child(2){width: 350px;margin-right: 0;}
      .rqsn-ln-6 span:nth-child(2) input{width: 300px;margin-right: 0;}

      .rqsn-ln-7 span{width: 785px;margin-right: 0;}
      .rqsn-ln-7 span input{width: 660px;margin-right: 0;}



      .rqsn-ln-8 span{    width: 100px;
          margin: 50px 28px 0px;
          font-size: 14px;
          text-align: center;
          font-style: italic;
          line-height: 15px;
          border-top: 1px solid #999;
      }
      /* .rqsn-ln-8 span input{width: 660px;margin-right: 0;} */


      .rqsn-ln-9{
      text-align: right;
        font-size: 16px;        
        padding: 0;
        margin: 0;
    }
      .rqsn-ln-9 span{
        text-align: left;
          font-size: 16px;          
          padding: 5px;
          display: block;
          width: 165px;
          background-color: #000;
          color: #fff;
          margin: 10px 0 0;
          float: right;
          text-transform: uppercase;
          
          
      }


      .rqsn-ln-10 span:nth-child(1){
        width: 300px; 
        float: right;
        margin-right: 0;
        text-align: left;
      }
      .rqsn-ln-10 span:nth-child(1) input{width: 255px;margin-right: 0;}

      .rqsn-ln-11 span:nth-child(1){width: 390px; }
      .rqsn-ln-11 span:nth-child(1) input{
        width: 275px;
        border: 1px solid #999;
        margin-right: 0;
      }
      .rqsn-ln-11 span:nth-child(2){width: 380px;margin-right: 0;}
      .rqsn-ln-11 span:nth-child(2) input{
        width: 250px;
        border: 1px solid #999;
        margin-right: 0;
      }

      .rqsn-ln-12 span:nth-child(1){width: 770px; }
      .rqsn-ln-12 span:nth-child(1) input{
        width: 705px;
        margin-right: 0;
      }
      .rqsn-ln-12 span:nth-child(2){width: 10px;margin-right: 0;}

      .rqsn-ln-13 span:nth-child(1){width: 430px;}
      .rqsn-ln-13 span:nth-child(1) input{width: 315px;margin-right: 0;}
      .rqsn-ln-13 span:nth-child(2){width: 350px;margin-right: 0;}
      .rqsn-ln-13 span:nth-child(2) input{width: 300px;margin-right: 0;}

      .rqsn-ln-14 span:nth-child(1){width: 350px; }
      .rqsn-ln-14 span:nth-child(1) input{
        width: 235px;
        margin-right: 0;
      }
      .rqsn-ln-14 span:nth-child(2){width: 70px;margin-right: 0;}



      .rqsn-ln-15 span{    width: 140px;
          margin: 50px 60px 0px;
          font-size: 14px;
          text-align: center;
          font-style: italic;
          line-height: 15px;
          border-top: 1px solid #999;
      }
      .rqsn-ln-16{ 
        text-align: center;
        border-bottom: 1px solid #999;
        font-size: 13px;
        font-style: italic;
        width: 800px;
        float: left;
        padding: 3px;
        
      }
  </style>
    
</head>
<body>

<button class="print-button btn btn-primary btn">Print</button>

<div class="lik-uftcl-rqsn-main-body">
  <div class="lik-uftcl-rqsn-print-body">
    <form method="" action="">
        <div class="lik-uftcl-pdf-body">
            <!-- body header part section start -->
          <div class="rqsn-header-part">
            <!-- body header part left section start -->
            <div class="rqsn-left-header">                      
              <p><img src="images/UFTCL-header-logo.png" alt=""></p>              
            </div>
            <!-- body header part left section end -->

            <!-- body header part right section start -->
            <div class="rqsn-right-header">
              <p>TREC Holder of Dhaka & Chittagong Stock Exchange Ltd</p>
              <p>TREC No. DSE 227 & CSE 043. Full Service Depository Pa rticipant of CDSL, DP No.31 1 00</p>
              <p>Shadharan Bima Tower (7th Floor), 37lA Dilkusha CiA Dhaka-1 000,Tel ;71 24769,717a169,Fax:9571</p>
              <p> Registered office:Suite No- 203 (1st Floo4,9/E Motijheel C/A,Dhaka-100</p>
            </div>
            <!-- body header part right section end -->
          </div>
        
          <!-- body top section start -->
          <p class="rqsn-ln-1">
            <span>REQUISITON SLIP</span>
          </p>
          
          <div class="rqsn-body-top">
            <p class="rqsn-ln-2">
              <span>To</span>
              <span>HO/CTG/Sylhet/Dhanmondi/Annex Branch</span>
            </p>
            <p class="rqsn-ln-3">
              <span>UNITED FINANCIAL TRADING COMPANY LIMITED</span>
              <span>Date :
                <input type="text" value="{{ date("D jS F Y g.iA", strtotime($get_data->created_at)) }}">
              </span>
            </p>
          
            <p class="rqsn-ln-4">
              <span>Client's Code No.

                <input type="text" value="&nbsp;&nbsp;{{ $get_data->client_code[4] }}">
                <input type="text" value="&nbsp;&nbsp;{{ $get_data->client_code[3] }}">
                <input type="text" value="&nbsp;&nbsp;{{ $get_data->client_code[2] }}">
                <input type="text" value="&nbsp;&nbsp;{{ $get_data->client_code[1] }}">
                <input type="text" value="&nbsp;&nbsp;{{ $get_data->client_code[0] }}">
              </span>
              <span>Mobile No.
                <input type="text" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $get_data->mobile }}">
              </span>
            </p>
            <p class="rqsn-ln-5">
              <span>Client Name :
                <input type="text" value="{{ $get_data->name }}">
              </span>
            </p>
            <p class="rqsn-ln-6">
              <span>Client's Bank Name :
                <input type="text" value="{{ $get_data->bank_name }}">
              </span>
              <span>Branch :
                <input type="text" value="{{ $get_data->branch_name }}">
              </span>
            </p>
            <p class="rqsn-ln-7">
              <span>Client's Bank A/C No. :
                <input type="text" value="{{ $get_data->account_no }}">
              </span>
            </p>
          
            <p class="rqsn-ln-8">
              <span>CIient/POA Signature</span>
              <span>Dealer Signature</span>
              <span>Settlement Signature</span>
              <span>Accounts Signature</span>
              <span>Approved By Signaturs</span>
            </p>            
            
          </div>            
          <!-- body top section end -->


          
          <!-- body mid section start -->
          <p class="rqsn-ln-9">
            <span>Payment Voucher</span>
          </p>
        
          <div class="rqsn-body-mid"> 
            <p class="rqsn-ln-10">
              <span>SL.No. 
                <input type="text">
              </span>
            </p>
            <p class="rqsn-ln-11">                
              <span>Ledger  Balance <b>TK :</b>
                <input type="text" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger_balance }}/-">
              </span>
              <span>Withdraw Amount <b>TK :</b> 
                <input type="text" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $get_data->amount }}/-">
              </span>
              
            </p>
            <p class="rqsn-ln-12">
              <span>Inword (TK)
                <input type="text" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($num_in_word) }} TAKA ONLY">
              </span>
              <span>)</span>
            </p>
            <p class="rqsn-ln-13">
              <span>EFT/Check No. : 
                <input type="text">
              </span>
              <span>Dated : 
                <input type="text" value="{{ date("D jS F Y g.iA", strtotime($get_data->created_at)) }}">
              </span>
            </p>
            <p class="rqsn-ln-14">
              <span>Drawn on City/Prim
                <input type="text">
              </span>
              <span>- Bank Ltd.</span>
            </p>
            <p class="rqsn-ln-15">
              <span>Cheque Receiver's</span>
              <span>Cheque Delivered by</span>
              <span>In Charge Accounts</span>
            </p>
          </div>
            <p class="rqsn-ln-16">Note : At this demand slip is to be presented to UFTCL office 24 houres requirment time.</p>

          <!-- body mid part section end -->          
        </div>
      </div>
    </form>
  </div>
</div>

<!-- jQuery 3 -->
<script src="{{ asset('admin/js/jquery.min.js') }}"></script>
<script>
  $(document).ready(function() {
    $('.sidebar-menu > li.all_user_withdrawal').addClass("active");
  });

  $(".print-button").on("click", function() {
    var url = "{{ URL::to('all_user_withdrawal') }}";
    $(this).hide();
    $(".main-footer").hide();
    window.print();
    window.location = url;
  });

</script>

</body>
</html>