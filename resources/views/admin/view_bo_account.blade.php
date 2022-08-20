<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>&nbsp;</title>
	<!-- <link rel="stylesheet"  type="text/css"  href="print.css" media="print" /> -->

	<!-- <style type="text/css" media="all"> @import "print.css";</style> -->
	<!-- <style type="text/css" media="all"> @import {{ asset('admin/print.css') }}</style> -->
	<link rel="stylesheet" type="text/css" media="all" href="{{ asset('assets/admin/print.css') }}" />

</head>

<body>
	<div class="lik-uftcl-main-body">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
				<div class="lik-uftcl-pdf-body">
					<p class="hdr-pt-1">
						<span class="hdr-pt-lft">
							<span class="cmpny-lgo">
								<img style="height:68px!important;width: 159px!important;" src="{{ asset('assets/logo/cygnus.png') }}" alt="UFTCL">
							</span>
							<span class="cmpny-frm-ttl">
								Customer Account Information
							</span>
							<span class="custm-acc-no">
								<span>Security A/c No. :</span>
								<!-- <span>
									<input type="text">
								</span>
								<span>
									<input type="text">
								</span>
								<span>
									<input type="text">
								</span>	 -->
								<input type="text" value="" style="width:300px">
							</span>
						</span>
						<span class="hdr-pt-rigt">
							<span class="custm-pic">
								<?php if($get_data->first_holder_picture) : ?>
									<img width="140" height="160" src="{{ asset('custom_files/bo_files/'.$get_data->first_holder_picture) }}">
								<?php endif; ?>
							</span>
							<span class="custm-pic">
								<?php if($get_data->bo_type=='Joint Account') : ?>
									<?php if($get_data->second_holder_picture) : ?>
										<img width="140" height="160" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_picture) }}">
									<?php endif; ?>
								<?php endif; ?>
							</span>
						</span>
					</p>

					<p class="ln-1 first-page-line">
						<span>Account Type : </span>
						<span>Cash 
							<input type="checkbox" checked>
						</span>
						<span>Margin 
							<input type="checkbox">
						</span>
						<span>Spical Remark if any :
							<input type="text"></span>
						<span>Photograph of Account Holder</span>
					</p>
				    <p class="ln-2 first-page-line">
				    	<span>Name of the Customer :
				    		<input type="text" value="{{ $get_data->bo_title .' '. $get_data->name_of_first_holder .' '. $get_data->bo_middle_name .' '. $get_data->bo_last_name. ' ' . $get_data->bo_short_name }}">
				    	</span>
				    </p>
					<p class="ln-3 first-page-line">
						<span>Father's/Husband's/CEO's(in case of Firm or Company) Name :
							<input type="text" value="{{ $get_data->father_or_husband_name }}">
						</span>
					</p>
					<p class="ln-4 first-page-line">
						<span>Mother's Name :
						<input type="text" value="{{ $get_data->mother_name }}">
						</span>
					</p>
					<p class="ln-5 first-page-line">
						<span>Date of Birth :
							<input type="text" value="{{ $get_data->date_of_birth }}">
						</span>
						<span> Age :
						<?php $age = (date('Y') - date('Y',strtotime($get_data->date_of_birth))); ?>

							<input type="text" value="{{ $age }}">
						</span>
						<span> Sex :</span>
						<span>
							<input type="checkbox" {{ ($get_data->sex_code=='Male') ? "checked" : "" }}>
							Male
						</span>
						<span>
							<input type="checkbox" {{ ($get_data->sex_code=='Female') ? "checked" : "" }}> 
							Female,
						</span>
						<span>Occupation 
							<input type="text" value="{{ $get_data->occupation }}">
						</span>
					</p>
					<p class="ln-6 first-page-line">
						<span>Present address : 
							<input type="text" value="{{ $get_data->address_1 }}">
						</span>
					</p>
					<p class="ln-7 first-page-line">
						<span>Permanent address : 
							<input type="text" value="{{ $get_data->address_2 }}">
						</span>
					</p>
					<p class="ln-8 first-page-line">
						<span>Tel (if any) : 
							<input type="text" value="{{ $get_data->phone_number }}">
						</span>
						<span> Nationality : 
							<input type="text" value="{{ ($get_data->nationality=='BAN') ? 'Bangladeshi' : $get_data->nationality }}">
						</span>
						<span>National ID : 
							<input type="text" value="{{ $get_data->first_holder_national_id }}">							
						</span>
					</p>
					<p class="ln-9 first-page-line">
						<span>Name Of Joint Account Holder :
							<input type="text" value="{{ $get_data->second_holder_title .' '. $get_data->second_joint_holder .' '. $get_data->second_holder_middle_name .' '. $get_data->second_holder_last_name. ' ' . $get_data->second_holder_short_name }}">
						</span>
					</p>
					<p class="ln-10 first-page-line">
						<span>Father's/Husband's Name :
							<input type="text" value="{{ $get_data->second_holder_father_name }}">
						</span>
					</p>
					<p class="ln-11 first-page-line">
						<span>Mother's Name :
						<input type="text" value="{{ $get_data->second_holder_mother_name }}">
						</span>
					</p>
					<p class="ln-12 first-page-line">
						<span>Date of Birth :
							<input type="text" value="{{ $get_data->second_holder_date_of_birth }}">
						</span>
						<span> Age : 
							<input type="text">
						</span>
						<span> Sex :</span>
						<span>
							<input type="checkbox">
							Male
						</span>
						<span>
							<input type="checkbox"> 
							Female,
						</span>
						<span>Occupation 
							<input type="text" value="{{ $get_data->second_holder_occupation }}">
						</span>
					</p>
					<p class="ln-13 first-page-line">
						<span>Present address : 
							<input type="text" value="{{ $get_data->second_holder_present_address }}">
						</span>
					</p>
					<p class="ln-14 first-page-line">
						<span>Permanent address : 
							<input type="text" value="{{ $get_data->second_holder_permanent_address }}">
						</span>
					</p>

					<p class="ln-15 first-page-line">
						<span>Tel (if any) : 
							<input type="text" value="{{ $get_data->second_holder_mobile }}">
						</span>
						<span> Nationality : 
							<input type="text" value="{{ $get_data->second_holder_nationality }}">
						</span>
						<span>National ID : 
							<input type="text" value="{{ $get_data->second_holder_national_id }}">							
						</span>							
					</p>
					<p class="ln-16 first-page-line">
						<span>Name with Address of the Authorized Person of the Customer,if Applicable :
							<input type="text">
						</span>
					</p>
					<p class="ln-17 first-page-line">
						<span>
							<input type="text">
						</span>
					</p>
					<p class="ln-18 first-page-line">
						<span>Officer or Director of any Stock Exchange/Listed Company?  :</span>
					   <span> Yes 
					   		<input type="checkbox">
					   </span>
					   <span> No 
					   		<input type="checkbox">
					   </span>
					</p>
					<p class="ln-19 first-page-line">
						<span>If yes, Name of the Stock Exchange/Listed Company : 
							<input type="text">
						</span>
					</p>
					<p class="ln-20 first-page-line">
						<span>Bank name : 
							<input type="text" value="{{ $get_data->bank_name }}">
						</span>
					   <span>Branch : 
					   		<input type="text" value="{{ $get_data->bank_branch_name }}">
					   </span>
					</p>
					<p class="ln-21 first-page-line">
						<span>A/C no : 
							<input type="text" value="{{ $get_data->bank_account_number }}">
						</span>
					   <span>Routing no : 
					   		<input type="text" value="{{ $get_data->bank_routine_number }}">
					   </span>
					</p>
					<p class="ln-22 first-page-line">
						<span>Beneficiary Owner Account No. : 
							<input type="text">							
						</span>	
						<span>
							<input type="text">							
						</span>	

					</p>
					<p class="ln-23 first-page-line">
						<span>Name &amp; Address of the Person Introducing the Customer, if any : 
							<input type="text">
						</span>
					</p>
					<p class="ln-24 first-page-line">
						<span> 
							<input type="text">
						</span>
					</p>
					<p class="ln-25 first-page-line">
						<span>Special lnstruction, if any : 
							<input type="text">
						</span>
					</p>
					<p class="ln-26 first-page-line">
						<span>Mode of operation : (Jointly/Any one can operate) : 
							<input type="text">
						</span>
					</p>
					
					<!-- footer signature -->
				
					<p class="sig-ln-1" style="margin-top: 30px">
						<span class="sig-1">
							<span>Signature of the Authorized Person of the Customer, if any </span>
							<span>Date : 
								<input type="text">
							</span>
						</span>
						
						<span class="sig-2">
							<span>Signature of the Person lntroducing the Customer </span>
							<span>Date : 
								<input type="text">
							</span>
						</span>
					</p>
					<p class="sig-ln-2" style="margin:0;padding:0;">
						<span class="sig-bt-1">
								@if($get_data->signature)
									<img width="100" src="{{ asset('custom_files/bo_files/'.$get_data->signature) }}"><br>
								@else
									<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
								@endif
							<span style="font-size:12px">
								Signature of  the Customer
							</span>
							<span>Date : 
								<input type="text" value="{{ date('Y-m-d') }}">
							</span>
						</span>
					
						<span class="sig-bt-2">
								<img width="140" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
							<span style="font-size:12px">Signature of the Member/Member's Accepting the Account </span>
							<span>Date : 
								<input type="text" value="{{ date('Y-m-d') }}">
							</span>
						</span>
					
						<span class="sig-bt-3">
								@if($get_data->bo_type=='Joint Account')
									@if($get_data->second_holder_signature)
										<img width="100" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_signature) }}"><br>
									@else
										<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
									@endif
								@endif
							<span style="font-size:12px">Signature of the Joint Account Holder </span>
						
							<span>Date : 
								<input type="text" value="{{ date('Y-m-d') }}">
							</span>
						</span>
					</p>
				</div>
				
			</form>
		</div>
	</div>

	<!-- page 2 -->
	<!-- <br><br> -->
	<div class="lik-uftcl-main-body" style="margin-top:-30px;height:1180px">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
				<div class="lik-uftcl-pdf-body">
					<h3 class="p2-ln-1">TERMS AND CONDITIONS</h3>
					<p class="p2-ln-2">"BROKER' shall mean UNITED FINANCIAL TRADING COMPANY LIMITED.</p>
					<p class="p2-ln-3">"BUYER' shall mean the person or persons or company who intends to buy securities through the BROKER.</p>
					<p class="p2-ln-4">"SELLER' shall mean the person or persons or company who intends to sell his/her/their securities through the BROKEB.</p>
					<p class="p2-ln-5">"Securities account" shall mean the account opened by the SELLER/BUYER with the BROKER to sell/buy securities.</p>
					<p class="p2-ln-6">"securities Day" shall mean the days declared by the Stock Exchange, on which transactions carried out by the BROKER on
						behalf of the SELLER/BUYER at the Stock Exchange are settled/cleared with Stock Exchange.</p>
					<p class="p2-ln-7">"CDBL" shall mean Central Depository Bangladesh Limited was incorporated 20th August 2000 in Bangladesh.</p>	
					<h3 class="p2-ln-8">
						<span>SALE ORDER</span>
					</h3>
					<p class="p2-ln-9">The SELLER shall deliver to the BROKER valid and negotiable documents. i.e., transfer/s documents duly completed and
					   signed by the SELLER together with relative securities certificates with valid title, prior to placing a sale order. lf for any
			           reason whatsover securities documents delivered by the SELLER turns out to be forged, invalid, worn out, torn or defaced,
			           the defaulting SELLER shall be liable to his BROKER for any loss or damage sustained or incurred. The defaulting SELLER
			           shall be liable to replace such securities along with all benifits attributable to such securities within two days of reporting in
			           writing to the SELLER by the BROKER. lf for any reason the defaulting SELLER fails to replace such securities along with all
			           benifits attributable to such securities within two days of reporting in writing to the SELLER by the BROKER, The BROKER
			           shall have the absulate discreting to square-up the tansation commencing from the market day after the stipulated period (as
			           above), at the SELLERS risk and the SELLER shall be liable to the BRoKER for any loss or damage sustained or lncuared.</p>
					<h3 class="p2-ln-10">
						<span>PAYMENT TO SELLER</span>
					</h3>   
					<p class="p2-ln-11">The BROKER shall make payment to the SELLER on the settlement day, subject to the overall cash balance of the Seller's
			           "Securities Account"</p>
					<h3 class="p2-ln-12">
						<span>PAYMENT BY BUYER</span>
					</h3>   
					<p class="p2-ln-13">The BUYER shall pay his BROKER on or before the settlement day balance amount (if any) including charges of all
			           securities purchased by him during the period of dealing for that settlement. lf the BUYER defaults for whatever reason, he
			           shall be liable to his BROKER for all loss or damage sustained or incurred. ln addition, to adjust the outstanding amount, the
			           buying BROKER shall have the absoulate discretion, to resell commencing from the market day after the day so settlement,
			           the securities at the BUYER'S risk and the BUYER shall be liable to the buying BROKER for any loss or damage sustained
			           incurred.</p>
					<h3 class="p2-ln-14">
						<span>SETTLEMENT THROUGH CDBL</span>
					</h3>  
					<p class="p2-ln-15">lf the CDBL is involved in the settlement process client should follow the under mention rules.</p>
					<p class="p2-ln-16">Client must maintain a Beneficiary Owner account with any depository participant, and client must inform the broker his BO
			           account number with authentic document.</p>
					<p class="p2-ln-17">Before place any sell order client must transfer his shares from his BO account to broker clearing account with related
			           instruction.</p>
					<p class="p2-ln-18">Client will pay the charges of CDBL, if necessary to transfer the shares from client BO account to broker clearing account
			           and clearing account to BO account</p>
					<p class="p2-ln-19">Broker reserve the absolute right to deduct the charges at source where applicable related to client CDBL operation.</p>
					<p class="p2-ln-20">Client will be liable any losses or damages occurred due to wrong or incorrect information related to CDBL is given by the client.</p>

				</div>				
			</form>
		</div>
	</div>

	<!-- page 3 -->
	<!-- <br><br><br> -->
	<div class="lik-uftcl-main-body" style="margin-top:-100px;height:1180px">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
				<div class="lik-uftcl-pdf-body">

					<h3 class="p3-ln-1">
						<span>GOVERNING LAW</span>
					</h3>
					<p class="p3-ln-2">All transactions shall be subject to the rules and regulations, of the Securities anci Exchange Commission/ Dhaka Stock Exchange Limited, Chittagong Stock Exchange Limited, CDBL and other prevailing laws and regulations of Bangladesh and in pafticular the authority hereinafter granted by the Client to the BROKER.</p>
					<h3 class="p3-ln-3">
						<span>AUTHORITY OF THE BROKER</span>
					</h3> 
					<p class="p3-ln-4">Broker reserves the absolute right for sale/buy/make adjustment/transfer any at client's risk in order to set cf al losses, damages and debit amount/shares/securities of "Client Account"</p>
					<p class="p3-ln-5">
						<span>Client shall be bound to
							<input type="text">
						</span>
					   <span>%( in word 
					   		<input type="text">)
					   </span>
					</p>
					<p class="p3-ln-6">charges as brokerage to broker for buy and sell and broker can change time to time.</p>
					<p class="p3-ln-6">Client shall be bound to furnish such other particulars, documents and/or information that may reasonably require from time to time. Broker shall have the rignt to change /modify any terms/conditions when may deem necessary withoui any notice to the client. We hereby accept your above terms and conditions and we declare that the information given is true and correct.</p>
					<p class="p3-ln-7" style="margin-top:10px">
						<span>
								@if($get_data->signature)
									<img style="margin-left:200px" width="100" src="{{ asset('custom_files/bo_files/'.$get_data->signature) }}"><br>
								@else
									<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
								@endif
							<span>Signature of Customer : 1.</span>
							<input type="text">
						</span>
					</p>
					<p class="p3-ln-8" style="margin-top:0px">
						<span>
								@if($get_data->bo_type=='Joint Account')
									@if($get_data->second_holder_signature)
										<img style="margin-left:200px" width="100" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_signature) }}"><br>
									@else
										<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
									@endif
								@endif

							<span>2.</span>
							<input type="text">
						</span>
					</p>
					<p class="p3-ln-9">
						<span>
							<span>Witness : 1. Signature </span>
							<input type="text">
						</span>
						<span>
							<span> 2. Signature </span>
							<input type="text">
						</span>
					</p>
					<p class="p3-ln-10">
						<span>
							<span>Name</span>
							<input type="text">
						</span>
						<span>
							<span>Name</span>
							<input type="text">
						</span>
					</p>
					<p class="p3-ln-11">
						<span>
							<span>Address</span>
							<input type="text">
						</span>
						<span>
							<span>Address</span>
							<input type="text">
						</span>
					</p>
										

					<div class="p3-ln-12">
					
						<h3 class="p3-ln-13"> FOR OFFICE USE ONLY : </h3>
						<p class="p3-ln-14">
							<span>
								<span>Introduced by : </span>
								<input type="text">
							</span>
							<span>
								<span>Approved by : </span>
								<input type="text">
							</span>
						</p>
						<p class="p3-ln-15">
							<span>
								<span>Signature : </span>
								<input type="text">
							</span>
							<span>
								<span>Signature : </span>
								<input type="text">
							</span>
						</p>
						<p class="p3-ln-16">
							<span>
								<span>Name : </span>
								<input type="text">
							</span>
							<span>
								<span>Name : </span>
								<input type="text">
							</span>
						</p>						
					</div>
				</div>				
			</form>
		</div>
	</div>

	<!-- page 4 -->
	<div class="lik-uftcl-main-body" style="height:1180px">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
				<div class="lik-uftcl-pdf-body">
					<p class="p4-ln-1">
						<span>
							<span>To</span>
							<span>Managing Director &amp; CEO</span>
							<span>UNITED FINANCIAL TRADING COMPANY LIMITED</span>
							<span>TREC No.- DSE:227 &amp; CSE : 043</span>
							<span>Sadharan Bima Tower (7th Floor)</span>
							<span>37lA, Dilkusha, Dhaka-1000.</span>
				            <span>Dear Sir</span>
							<span>LETTER OF AUTHORISATION</span>			
						</span>
						<!-- <span>
							<input type="text" style="display:block;">							
							<span>Photograph of Authoraize Person</span>
						</span> -->
						<span class="custm-pic" style="border: 1px solid #ddd;float:right;overflow:hidden;width:160px;height:200px">
								@if($get_data->authorize_person_photo)
									<img width="100%" src="{{ asset('custom_files/bo_files/'.$get_data->authorize_person_photo) }}"><br>
								@endif
						</span>
					</p>
					<p class="p4-ln-2">		
						<span> I/We 
							<input type="text">
						</span>
						<span> do/so/wo 
							<input type="text">
						</span>
						<span>of</span>
					</p>
					<p class="p4-ln-3">
						<span>
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-4">
						<span>hereby authorize Mr./Mrs 
							<input type="text">
						</span>
						<span>do/so/wo 
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-5">
						<span> of 
							<input type="text">
						</span>
					</p>	
					<p class="p4-ln-6">
						<span>
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-7">
						<span>
							<input type="text">
							whose speciman signature is given below ( hereinafter referred to as the "Account Operator") to exclusively deal, buy, sell, transfer shares, debenture stocks,bonds and other securities on behalf of me/us with regard to the Securities Account" opened and maintained in my name with M/s <b>UNITED FINANCIAL TRADING COMPANY LIMITED</b> submitted (thereinafter referred to as the "Broker").
						</span>
						
					</p>
					<p class="p4-ln-8">I hereby authorize and instruct the "Broker'to deal, buy, sell, transfer shares, stocks, debentures, debenture stocks bonds and other securities on verbal 
					   and/or written instructions of the "Account Operator".</p>
					<p class="p4-ln-9">I also authorize the "Account or operator" to place buy/sell orders. Receive confirmation notes, receive and deliver Chaques/cash and shares other securities on my/our behalf with regard to my/our "Securities Account".</p>  
					<p class="p4-ln-10">I hereby declare that l/we am full aware of all consequences of transaction that may be carried out on my/our behalf by the "Account operator" and shall take responsibility of all such transaction as that of my/our own. I/we shall fulfill and abide by all rules and regulation described in the "Securities Account Opening Form" duly completed and signed by me/us, with regard to all transaction carried out by the "Account Operator" without any demur of protest.</p> 
					<p class="p4-ln-11">I hereby undertake and ensure of make good and compensate for any loss or damage incurred and sustained by the
				      "Broker" for any reason whatsoever as a result of any transaction carried out by the "Account Operator".</p>
					<p class="p4-ln-12">
						<span>
							<input type="checkbox"> 
							<span>Cheque Collect </span> 
						</span>
					   	<span>
						   	<input type="checkbox"> 
						   	<span>Cheque Diposit </span>
					   	</span>
					  	<span>
						   	<input type="checkbox"> 
						   	<span>Share Collect </span>
					   	</span>
					   	<span>
						   	<input type="checkbox"> 
						   	<span>Share Diposit </span>
					   	</span>
					   	<span>
						   	<input type="checkbox"> 
						   	<span>Portfolio Statement Collect </span>
					   	</span>
					   	<span>
						   	<input type="checkbox"> 
						   	<span>Buy/Sell/ Order slip Deposit </span>
					   	</span>
					</p>
					<p class="p4-ln-13">
						<span> Thank you </span>
					</p>   
					<p class="p4-ln-14">
						<span> Yours Sincerely </span>		
						<span><span>(Signature of Account Operator)</span></span>
					</p> 
					<p class="p4-ln-15">
						<span> 1. 
							<input type="text">
						</span>
						<span> 2. 
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-16">
						<span> 1. 
							<input type="text">
						</span>
						<span> 2. 
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-17">
						<span> Date: 
							<input type="text">
						</span>
						<span>(Attested by Account Holder)</span>
					</p>
					<p class="p4-ln-18">
						<span>
							<span>witness: 1. Signature :</span>
							<input type="text">
						</span>
						<span>
							<span>1. Signature :</span>
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-19">
						<span>
							<span> Name :</span>
							<input type="text">
						</span>
						<span>
							<span> Name :</span>
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-20">
						<span>
							<span> Address :</span>
							<input type="text">
						</span>
						<span>
							<span> Address :</span>
							<input type="text">
						</span>
					</p>
					<p class="p4-ln-21">
						<span>
							<span></span>
							<input type="text">
						</span>
						<span>
							<span></span>
							<input type="text">
						</span>
					</p>					
				</div>				
			</form>
		</div>
	</div>

	<!-- page 5 -->
	<div class="lik-uftcl-main-body" style="height:1180px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
				<div class="lik-uftcl-pdf-body">
					<div class="p5-ln-1">
						<span>
							<img src="images/UFTCL-header-logo.png" alt="">
						</span>
						<span>
							<h3>BO Account opening Form </h3>
							<p> By law 7.3.3 (b) </p>
						</span>
					</div>
					<p class="p5-ln-2">Please complete all details in CAPITAL latters. Please fill all name correctly. 
					   All communication shall be sent to the First Named Account Holder's correspondenc address. </p>
					<div class="p5-ln-3">
						
						<span> 
							<span>Application No. : </span>
							<span><input type="text"></span>
						</span>
						
						<span>Please Tick whichever is applicable </span>
						
					   	<span> Date : (DD/MM/YYYY) 
					   		<input type="text" value="{{ date('Y-m-d') }}">
					   	</span>
					</div>  
					<div class="p5-ln-4">
					   	<span> <b>BO Category :</b> Raguler 
					   		<input type="checkbox" {{ ($get_data->bo_category=='Regular') ? "checked" : "" }}>
					   	</span>
					   	<span> Omnibus 
					   		<input type="checkbox" {{ ($get_data->bo_category=='Omnibus') ? "checked" : "" }}>
					   	</span>
					   	<span> Clearing 
					   		<input type="checkbox" {{ ($get_data->bo_category=='Clearing') ? "checked" : "" }}>
					   	</span>
					   	<span> Bo Type : Individual 
					   		<input type="checkbox" {{ ($get_data->bo_type=='Individual') ? "checked" : "" }}>
					   	</span>
					   	<span> Company 
					   		<input type="checkbox" {{ ($get_data->bo_type=='Company Account') ? "checked" : "" }}>
					   	</span>
					   	<span> Joint Holder
					   		<input type="checkbox" {{ ($get_data->bo_type=='Joint Account') ? "checked" : "" }}>
						</span>
					</div>

					<div class="p5-ln-4 l1" style="font-size:10px;height:70px">
					  	<span style="font-size:12px"> Name of CDBL Participant (up to 99 Character) : <b>UNITED FINANCIAL TRADING COMPANY LIMITED </b></span>
					  	<span>CDBL participant ID</span>
					  	<span>  BO ID</span> 
					  	<span> Date of Account opened day ( DD MM YYYY) </span>

					  	<span> 
					  		<input type="text">
					  	</span>
					  	
					  	<span>
					  		<input type="text"> 
					  	</span>

					  	<span>
					  		<input type="text">
					  	</span>

					  	<span>
					  		<input type="text">
					  	</span>
					</div>

					<p class="p5-ln-5" style="font-size:12px;height:15px;margin-top:-5px"><span> l/We request you to open a Depository Account in my / our name as per the following details :</span></p>

					<h3 class="p5-ln-6" style="font-size:15px"> 1. First Applicant </h3>
					<div class="p5-ln-7">
					   	<span> Name in full of Account Holder (up to 99 Characters) 
					   		<input type="text" value="{{ $get_data->name_of_first_holder .' '. $get_data->bo_middle_name .' '. $get_data->bo_last_name. ' ' . $get_data->bo_short_name }}">
					   	</span> 
					   	<span> Short Name of Account Holder (lnsert full name starting with Title i.e. Mr./Mrs./Ms/Dr, abbreviate only if over 30 Character </span>
					   <span> Title i.e. Mr/Mrs/Ms/Dr.</span>
					   	<span>
					   		<input type="text">
						</span>
						<span>
					   		<input type="text" value="{{ $get_data->bo_title }}">
					   	</span>
					   	<span> (ln case of Company/Firm/Statutory Body) Name of Contact Person 
					   		<input type="text"> 
					   	</span>
					   	<span> (ln case of lndividual) Male 
					   		<input type="checkbox" {{ ($get_data->sex_code=='Male') ? "checked" : "" }}>
					   	</span>
					   	<span> Female 
					   		<input type="checkbox" {{ ($get_data->sex_code=='Female') ? "checked" : "" }}> 
					   	</span>
					   	<span> Occupation (30 Characters) 
					   		<input type="text" value="{{ $get_data->occupation }}">
					   	</span>
					   	<span> Father's /Husband's Name : 
					   		<input type="text" value="{{ $get_data->father_or_husband_name }}">
					   	</span>
					   	<span> Mothers Name : 
					   		<input type="text" value="{{ $get_data->mother_name }}">
					   	</span>  
					</div>
					
					<h3 class="p5-ln-8" style="font-size:15px"> 2. Contact Details </h3>
					<div class="p5-ln-9">
					   	<span> Address 
					   		<input type="text" value="{{ $get_data->address_1 }}">
					   	</span>

					   	<span> City 
					   		<input type="text" value="{{ $get_data->city }}">
					   	</span>

					   	<span> Post Code 
					   		<input type="text" value="{{ $get_data->postal_code }}">
					   	</span>

					   	<span> State/Division 
					   		<input type="text" value="{{ $get_data->state }}">
					   	</span>

					   	<span> Country 
					   		<input type="text" value="{{ $get_data->country }}">
					   	</span>

					   	<span> Telephone
					   		<input type="text" value="{{ $get_data->phone_number }}">
					   	</span>

					   	<span> Mobile Phone
					   		<input type="text" value="{{ $get_data->phone_number }}">
					   	</span>

					   	<span> Fax 
					   		<input type="text" value="{{ $get_data->fax_number }}">
					   	</span>

					   	<span> Email 
					   		<input type="text" value="{{ $get_data->email_id }}">
					   	</span>

					   	<span> NID 
					   		<input type="text" value="{{ $get_data->first_holder_national_id }}">
					   	</span>

					</div>
					<h3 class="p5-ln-10" style="font-size:15px"> 3. Passport Details :</h3>
					<div class="p5-ln-11">
					   	<span> Passport No. 
					   		<input type="text" value="{{ $get_data->passport_number }}">
					   	</span>
					   	<span>  lssue place 
					   		<input type="text" value="{{ $get_data->passport_issue_place }}">
					   	</span>
					   	<span> Issue Date 
					   		<input type="text" value="{{ $get_data->passport_issue_date }}">
					   	</span>
					   	<span> Expiry Date 
					   		<input type="text" value="{{ $get_data->passport_expiry_date }}">
					   	</span>
					</div>

					<h3 class="p5-ln-12" style="font-size:15px"> 4. Bank Details </h3>
					<table border ="1" class="p5-ln-13">
						<tr>
							<td>Routing No </td>
							<td>{{ $get_data->bank_routine_number }}</td>
							<td>Bank lndentifier Code (BlC)</td>
							<td>{{ $get_data->bank_identification_code }}</td>
						</tr>
						<tr>
							<td>Bank Name </td>
							<td>{{ $get_data->bank_name }}</td>
							<td>Branch Name </td>
							<td>{{ $get_data->bank_branch_name }}</td>
						</tr>
						<tr>
							<td>Bank A/C No.</td>
							<td>{{ $get_data->bank_account_number }}</td>
							<td>District Name</td>
							<td>{{ $get_data->city }}</td>
						</tr>
						<tr>
							<td>SWIFT Code</td>
							<td>{{ $get_data->bank_swift_code }}</td>
							<td>lnternational bank A/C No.(BAN)</td>
							<td>{{ $get_data->international_bank_account_number }}</td>
						</tr>
					</table>

					<h3 class="p5-ln-14" style="font-size:15px"> 5. Electronics Devidend Credit:</h3>
					<div class="p5-ln-15">	
					   	<span> Yes 
					   		<input type="checkbox" {{ ($get_data->electronic_dividend_flag=="Yes") ? "checked" : "" }}>
					   	</span>

					   	<span> No 
					   		<input type="checkbox" {{ ($get_data->electronic_dividend_flag=="No") ? "checked" : "" }}>
					   	</span>

					   	<span>Tax Assumption if any. Yes
					   		<input type="checkbox" {{ ($get_data->tax_exemption_flag=="Yes") ? "checked" : "" }}>
					   	</span>

					   	<span>  No 
					   		<input type="checkbox" {{ ($get_data->tax_exemption_flag=="No") ? "checked" : "" }}>
					   	</span>

					   	<span> TIN/Tax ID :
					   		<input type="text" value="{{ $get_data->tax_identification_number }}">
					   	</span>

					</div>

					<h3 class="p5-ln-16" style="font-size:15px;"> Other Information : </h3>
					<div class="p5-ln-17" style="height:127px">
					  	<span> Residency: Resident 
					  		<input type="checkbox" {{ ($get_data->residency_flag=="Resident") ? "checked" : "" }}>
					  	</span>
					  	<span> Non Resident 
					  		<input type="checkbox" {{ ($get_data->residency_flag=="Non Resident") ? "checked" : "" }}>
					  	</span>
					  	<span> Nationality 
					  		<input type="checkbox" checked>
					  	</span>
					  	<span> Date of Birth (DD/MM/YYYY)
					  		<input type="text" value="{{ $get_data->date_of_birth }}">
					  	</span>
					  	<span> Statement Cycle Code: Daily 
					  		<input type="checkbox" {{ ($get_data->statement_cycle_code=="Daily") ? "checked" : "" }}>
					  	</span>
				  		<span> Weekly
					  		<input type="checkbox" {{ ($get_data->statement_cycle_code=="Weekly") ? "checked" : "" }}>
					  	</span>
					  	<span> Fortnightly
					  		<input type="checkbox">
					  	</span>
					  	<span> Monthly
					   		<input type="checkbox" {{ ($get_data->statement_cycle_code=="Monthly") ? "checked" : "" }}>
						</span>
					  	<span> Other ( please specify)
					  		<input type="text" {{ ($get_data->statement_cycle_code=="Other") ? "checked" : "" }}>
					  	</span>
					  	<span> Internal Ref No ( To be filled in by CDBL participant ) UFTCL# </span>

					  	<span> In Case of Company :</span>
					  	<span> Date of Registration (DD/MM/YYYY)</span>

					  	<span> Registration No. 
					  		<input type="text" value="{{ $get_data->registration_number }}">
					  	</span>
					  	<span>
					  		<input type="text">
					  	</span>

					</div>

					<h3 class="p5-ln-18" style="font-size:15px"> 7. Joint Applicant( Second Account Holder) : </h3>
					<div class="p5-ln-19">
					  	<span> Name in full (up to 99 Characters) 
					  		<input type="text" value="{{$get_data->second_joint_holder .' '. $get_data->second_holder_middle_name .' '. $get_data->second_holder_last_name. ' ' . $get_data->second_holder_short_name }}">
					  	</span>

					  	<span> NID 
					  		<input type="text" value="{{ $get_data->second_holder_national_id }}">
					  	</span>

					  	<span> Short Name of Account Holder (lnsert full name stariing with Title i.e. Mr/Mrs/Ms/Dr, abbreviate only if over 30 Characters) 
						</span>
					  	<span> Tiile i.e. Mr/Mrs/Ms/Dr</span>
					  	<span>
					  		<input type="text">
					  	</span>

					  	<span> 
					  		<input type="text" value="{{ $get_data->second_holder_title }}">
					  	</span>

					</div>
				</div>				
			</form>
		</div>
	</div><br>

	<!-- page 6 -->
	<div class="lik-uftcl-main-body" style="height:1160px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
				<div class="lik-uftcl-pdf-body">
					<h3 class="p6-ln-1"> 8. Account Link Request :</h4>
					<p class="p6-ln-2">
						<span> Would you like to create a link to your existing Depository ?</span>
						<span> Yes 
							<input type="checkbox">
						</span>
						<span> No 
							<input type="checkbox">
						</span>
					</p>
					<p class="p6-ln-3">
						<span> lf yes then please provide the Depository BO Account Code (8 Digits) 
							<input type="text">
						</span>
					</p>
					<h3 class="p6-ln-4"> 9. Nominees/ Heirs </h4>
					<p class="p6-ln-5" style="font-size:12px;height:90px">
						  <span>lf account holder (s) wish to nominate person (s) who will be entitled to receive securities outstanding in the account in the event of its death of the sole account holder/all the joint account holders, a separate nomination form-23 must be filled up and signed by all account holders and the nominees giving names of nominees, relationship with first account holder, percentage distribution and contact details. lf any nominee minor, guardians name, address, relationship with nominee has also be provided.</span>
					</p>
					<h3 class="p6-ln-6"> 10. Power of Attorney (POA) </h4>
					<p class="p6-ln-7" style="font-size:12px;height:42px">
						<span> If account holder (s) wish to given a power of Attorney (POA) to someone to operate the account, a separate form-2O must be filled up and signed by all account holders giving the name, contact details etc o the PoA holder and a PoA document lodged with the form.</span>
					</p>
					<h3 class="p6-ln-8"> 11. To be filled in by stock broker/ stok exchange in case the application is for opening a clearing account </h4>
					<div class="p6-ln-9">
					  	<span> Exchange name :</span>
					  	<span> DSE
					  		<input type="checkbox">
					  	</span>
					  	<span> Trading ID: 10 </span>
					  	<span>CSE 
					  		<input type="checkbox">
					  	</span>
					  	<span> Trading ID: 11</span>
					</div>
					<h3 class="p6-ln-10"> 12. Photograph</h4>
					<p class="p6-ln-11" style="height:190px">
					  	<span> 
					  		<!-- <input type=""> -->
								@if($get_data->first_holder_picture)
									<img style="border:1px solid #ddd;margin-left:60px" width="120" src="{{ asset('custom_files/bo_files/'.$get_data->first_holder_picture) }}"><br>
								@else
									<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
								@endif
					  		<span>1st Applicant or Authorized Signatory in case of Ltd. Co.</span> 
					  	</span>
					  	<span>
					  			<?php if($get_data->bo_type=='Joint Account') : ?>
									@if($get_data->second_holder_picture)
										<img style="border:1px solid #ddd;margin-left:60px" width="120" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_picture) }}"><br>
									@else
										<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
									@endif
								<?php endif; ?>
					  		<span>2nd Applicant or Authorized Signatory in case of Ltd. Co. </span>
					  	</span>
					  	<span>
					  			<?php if($get_data->bo_type=='Joint Account') : ?>
									@if($get_data->third_holder_picture)
										<img style="border:1px solid #ddd;margin-left:60px" width="120" src="{{ asset('custom_files/bo_files/'.$get_data->third_holder_picture) }}"><br>
									@else
										<img width="100" src="{{ asset('custom_files/bo_files/dummy.jpg') }}"><br>
									@endif
								<?php endif; ?>
					  		<span>Authorized Signatory in case of Ltd. Co.</span>
					  	</span>
					</p>
					<h3 class="p6-ln-12"> 13. Standing instructions </h4>
					<p class="p6-ln-13">
					  	<span> I/we authorize you to receive facsimile (fax) transfer instruction for delivery. </span>
					  	<span>Yes 
					  		<input type="checkbox">
					  	</span>
					  	<span> No 
					  		<input type="checkbox">
					  	</span>
					</p>
					<h3 class="p6-ln-14"> 14. Declaration</h4>
					<p class="p6-ln-15" style="font-size:11.5px;height:98px">
					   <span>The rules and regulations of the Depository and CDBL Participant pertaining to an account which are in force now have been
				       read by me/us and I/we have understood the same and l/we agree to abide by and to be bound by the rules as are in force
				       from time to time for such accounts. l/We also declared that the particulars given by me/us are ture to the best of my/our
				       knowledge as on the date of making such application. l/We further agree that any false/misleading information given by
				       me/us or supperession of any materail fact will render my/our account liable for termination and further action.</span>
					</p>
					<table border="1" width="800"  class="p6-ln-16">
						<tr>
							<th> Applicant </th>
							<th> Name of Applicant/Authorized Signatories in cas of Limited Co </th>
							<th> Signature with date </th>

						</tr>
						<tr>
						   <td> First Applicant </td>
						   <td>{{ $get_data->bo_short_name }}</td>
						   <td>
								@if($get_data->signature)
									<img style="margin-left:100px" width="30" src="{{ asset('custom_files/bo_files/'.$get_data->signature) }}"><br>
								@endif
						   </td>
						</tr>
						<tr>
						   <td> Second Applicant </td>
						   <td>{{ $get_data->second_holder_short_name }}</td>
						   <td>
						   		<?php if($get_data->bo_type='Joint Account') : ?>
									@if($get_data->second_holder_signature)
										<img style="margin-left:100px" width="30" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_signature) }}"><br>
									@endif
								<?php endif; ?>
						   </td>
						</tr>
						<tr>
						   <td> 3rd Signatory (Ltd. Co. only) </td>
						   <td>{{ $get_data->third_holder_short_name }}</td>
						   <td>
						   		<?php if($get_data->bo_type='Joint Account') : ?>
									@if($get_data->third_holder_signature)
										<img style="margin-left:100px" width="30" src="{{ asset('custom_files/bo_files/'.$get_data->third_holder_signature) }}"><br>
									@endif
								<?php endif; ?>
						   </td>
						</tr>
					</table>
					<h3 class="p6-ln-17"> 15. Spacial Instruction on Operation of Joint Account </h4>
					<p class="p6-ln-18">
					    <span>
					    	<input type="checkbox">
					    	 Either or Survivor 
					    </span>
					    <span>
					    	<input type="checkbox">
					    	 Any one can operate 
					    </span>
					    <span>
					    	<input type="checkbox">
					    	 Any two will operate jointly 
					    </span>
					    <span>
					    	<input type="checkbox">
					    	 Account will be operated by 
					    </span>
					    <span>
					    	<input type="text">
					    	 With any one of the others. 
					    </span>
					    <span>
					    	<input type="checkbox">
					    	 Operated by PoA 
					    </span>
					</p>
					<h3 class="p6-ln-19"> 16. Introduction </h4>
					<div class="p6-ln-20" style="font-size:12px;">
						<span>Introduction by an existins account holder of </span>
						<span>							
							<span>UNITED FINANCIAL TRADING COMPANY LIMITED </span>
							<span> Depository participant's name </span>
						</span>
						<span> Confirm the identity, occupation and address of the applicants (s) 							
							<input type="text">
						</span>
						
						<span>
							<input type="text">
						</span>
						<span>lntroducer's Name: </span>
						<span> Signature of introducer </span>
						<span> Account ID 
							<input type="text"> 							
						</span>		
					</div>
				</div>				
			</form>
		</div>
	</div>

	<!-- page 7 -->
	<div class="lik-uftcl-main-body" style="height:1160px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
					<div class="lik-uftcl-pdf-body">
					
						<h3 class="p7-ln-1"><span>BO Account Nomination Form</span></h3>
						<p class="p7-ln-2">
								<span>Please complete all details in CAPlTAL Letters.Please fill all name correctly.All communication shall be sent to the correspondence address of only the First Named Account Holder as spedified in Bo Account opening Form 02.</span>
						</p>
						<p class="p7-ln-3">
								<span>Application No. 
									<input type="text">
									
								</span>	
								<span>Date:(DD/MM/YYYY) 
									<input type="text">					
								</span>	
						</p>
						<div class=" main">
							<p class="p7-ln-4">
									<span>Name of CDBL Participant (up to Character)</span>
									<span>CDBL Participant ID</span>
							</p>
							<p class="p7-ln-5">
									<span>UNITED FINANCIAL TRADING COMPANY LIMITED</span>
									<span>
										<input type = "text">
									</span>

							</p>
							<p class="p7-ln-6">
									<span>Account Hotder's Bo ID
											<input type = "text">
									</span>
									<span>
										<input type = "text">
									</span>
									<span>
										<input type = "text">
									</span>
							</p>
							<p class="p7-ln-7">
									<span>Name of Account Holder (lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)
									</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p7-ln-8">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
						</div>
						<p class="p7-ln-9"> 
								<span>I/We nominiate the following Person(S) who is/are entitled to receive securities outstanding in my/our account in the event of the death of the sole holder/all the joint holders.</span>
						</p>
						
						<p class="p7-ln-10">
								<span>1.Nominee/Heirs Details</span>
						</p>
						<div class="p7-ln-11">
							<!-- Nominee 1  -->
							<p class="p7-ln-12">
								<span>Nominee 1 </span>
							</p>
							<p class="p7-ln-13">
								<span>Name in full <input type="text" value="&nbsp;&nbsp;&nbsp;&nbsp;{{ $get_data->nominee_first_name . " " . $get_data->nominee_middle_name . " " . $get_data->nominee_first_name }}"></span>
							</p>
							<p class="p7-ln-14">
									<span>Short name of nominee(lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p7-ln-15">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
							<p class="p7-ln-16">
									<span>Relationship with A/C Holder :
										<input type = "text"></span>
									<span>Percentage(%) :
										<input type = "text"></span>				
							</p>
							<p class="p7-ln-17">
									<span>Address :
										<input type="text" value="{{ $get_data->nominee_address_1 }}">
									</span>
							</p>
							<p class="p7-ln-18">
									<span>City
										<input type = "text" value="{{ $get_data->nominee_city }}"></span>
									<span>Post Code
										<input type = "text" value="{{ $get_data->nominee_postal_code }}"></span>
									<span>State/Division
										<input type = "text" value="{{ $get_data->nominee_state }}"></span>
									<span>Country
										<input type = "text" value="{{ $get_data->nominee_country }}"></span>
									<span>Telephone
										<input type = "text" value="{{ $get_data->nominee_phone_number }}"></span>
							</p>
							<p class="p7-ln-19">
									<span>Mobile Phone
										<input type = "text" value="{{ $get_data->nominee_phone_number }}">
									</span>
										<span>Fax
										<input type = "text">
									</span>
										<span>Email
										<input type = "text">
									</span>
							</p>
							<p class="p7-ln-20">
									<span>Passport No.
										<input type = "text" value="{{ $get_data->nominee_passport_number }}">
									</span>
										<span>Issue Place
										<input type = "text" value="{{ $get_data->nominee_passport_issue_place }}">
									</span>
										<span>Issue Date
										<input type = "text" value="{{ $get_data->nominee_passport_issue_date }}">
									</span>
										<span>Expiry Date
										<input type = "text"  value="{{ $get_data->nominee_passport_expiry_date }}">
									</span>
							</p>
							<p class="p7-ln-21">
									<span>Residency:</span>
									<span>Resident
										<input type="checkbox" {{ $get_data->nominee_residency_flag=="Resident" ? "checked" : "" }}>
									</span>
									<span>Non Resident
										<input type="checkbox" {{ $get_data->nominee_residency_flag=="Non Resident" ? "checked" : "" }}>
									</span>
									<span>Nailonatity
										<input type = "text" value="{{ $get_data->nominee_nationality }}">
									</span>
									<span>Dareof Birth(DD/MM/yyyy)
										<input type = "text" value="{{ $get_data->nominee_date_of_birth }}">
									</span>
							</p>
							<p class="p7-ln-22">
									<span>NID
										<input type = "text" value="{{ $get_data->nominee_nid }}">
									</span>
							</p>

							<!-- Guardian's Details (lf Nominee is a Minor) -->
							<p class="p7-ln-12" style="margin-top:15px">
								<span>Guardian's Details (lf Nominee is a Minor) </span>
							</p>
							<p class="p7-ln-13">
								<span>Name in full <input type = "text"></span>
							</p>
							<p class="p7-ln-14">
									<span>Short name of nominee(lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p7-ln-15">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
							<p class="p7-ln-16">
									<span>Relationship with A/C Holder :
										<input type = "text"></span>
									<span>Percentage(%) :
										<input type = "text"></span>				
							</p>
							<p class="p7-ln-17">
									<span>Address :
										<input type = "text">
									</span>
							</p>
							<p class="p7-ln-18">
									<span>City
										<input type = "text"></span>
									<span>Post Code
										<input type = "text"></span>
									<span>State/Division
										<input type = "text"></span>
									<span>Country
										<input type = "text"></span>
									<span>Telephone
										<input type = "text"></span>
							</p>
							<p class="p7-ln-19">
									<span>Mobile Phone
										<input type = "text">
									</span>
										<span>Fax
										<input type = "text">
									</span>
										<span>Email
										<input type = "text">
									</span>
							</p>
							<p class="p7-ln-20">
									<span>Passport No.
										<input type = "text">
									</span>
										<span>Issue Place
										<input type = "text">
									</span>
										<span>Issue Date
										<input type = "text">
									</span>
										<span>Expiry Date
										<input type = "text">
									</span>
							</p>
							<p class="p7-ln-21">
									<span>Residency:</span>
									<span>Resident
										<input type="checkbox">
									</span>
									<span>Non Resident
										<input type="checkbox">
									</span>
									<span>Nailonatity
										<input type = "text">
									</span>
									<span>Dareof Birth(DD/MM/yyyy)
										<input type = "text">
									</span>
							</p>
							<p class="p7-ln-22">
									<span>NID
										<input type = "text">
									</span>
							</p>
						</div>

					</div>
				</div>
			</form>
		</div>
	</div><br><br>

	<!-- page 8 -->
	<div class="lik-uftcl-main-body" style="height:1140px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
					<div class="lik-uftcl-pdf-body">
					
						<div class="p8-ln-1">
							<!-- Nominee 2  -->
							<p class="p8-ln-2">
								<span>Nominee 2 </span>
							</p>
							<p class="p8-ln-3">
								<span>Name in full <input type = "text"></span>
							</p>
							<p class="p8-ln-4">
									<span>Short name of nominee(lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p8-ln-5">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
							<p class="p8-ln-6">
									<span>Relationship with A/C Holder :
										<input type = "text"></span>
									<span>Percentage(%) :
										<input type = "text"></span>				
							</p>
							<p class="p8-ln-7">
									<span>Address :
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-8">
									<span>City
										<input type = "text"></span>
									<span>Post Code
										<input type = "text"></span>
									<span>State/Division
										<input type = "text"></span>
									<span>Country
										<input type = "text"></span>
									<span>Telephone
										<input type = "text"></span>
							</p>
							<p class="p8-ln-9">
									<span>Mobile Phone
										<input type = "text">
									</span>
										<span>Fax
										<input type = "text">
									</span>
										<span>Email
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-10">
									<span>Passport No.
										<input type = "text">
									</span>
										<span>Issue Place
										<input type = "text">
									</span>
										<span>Issue Date
										<input type = "text">
									</span>
										<span>Expiry Date
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-11">
									<span>Residency:</span>
									<span>Resident
										<input type="checkbox">
									</span>
									<span>Non Resident
										<input type="checkbox">
									</span>
									<span>Nailonatity
										<input type = "text">
									</span>
									<span>Dareof Birth(DD/MM/yyyy)
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-12">
									<span>NID
										<input type = "text">
									</span>
							</p>

							<!-- Guardian's Details (lf Nominee is a Minor) -->
							<p class="p8-ln-2" style="margin-top:15px">
								<span>Guardian's Details (lf Nominee is a Minor) </span>
							</p>
							<p class="p8-ln-3">
								<span>Name in full <input type = "text"></span>
							</p>
							<p class="p8-ln-4">
									<span>Short name of nominee(lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p8-ln-5">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
							<p class="p8-ln-6">
									<span>Relationship with A/C Holder :
										<input type = "text"></span>
									<span>Percentage(%) :
										<input type = "text"></span>				
							</p>
							<p class="p8-ln-7">
									<span>Address :
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-8">
									<span>City
										<input type = "text"></span>
									<span>Post Code
										<input type = "text"></span>
									<span>State/Division
										<input type = "text"></span>
									<span>Country
										<input type = "text"></span>
									<span>Telephone
										<input type = "text"></span>
							</p>
							<p class="p8-ln-9">
									<span>Mobile Phone
										<input type = "text">
									</span>
										<span>Fax
										<input type = "text">
									</span>
										<span>Email
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-10">
									<span>Passport No.
										<input type = "text">
									</span>
										<span>Issue Place
										<input type = "text">
									</span>
										<span>Issue Date
										<input type = "text">
									</span>
										<span>Expiry Date
										<input type = "text">
									</span>
							</p>
							<p class="p8-ln-11">
									<span>Residency:</span>
									<span>Resident
										<input type="checkbox">
									</span>
									<span>Non Resident
										<input type="checkbox">
									</span>
									<span>Nailonatity
										<input type = "text">
									</span>
									<span>Dareof Birth(DD/MM/yyyy)
										<input type = "text">
									</span>
							</p>
							
						</div>
						<p class="p8-ln-13">
								<span>2.Photograph of Nominees/Hirs</span>
						</p>
						<p class="p8-ln-14" style="height:170px">
								<span>
								@if($get_data->nominee_picture)
									<img style="border:1px solid #ddd;margin-left:50px" width="120" src="{{ asset('custom_files/bo_files/'.$get_data->nominee_picture) }}"><br>
								@else
									<img width="100" src="{{ asset('custom_files/bo_files/dummy-passport-image.jpg') }}"><br>
								@endif
								<span>Nominee/Heir 1</span>
								</span>
								<span>
									<img style="border:1px solid #ddd;margin-left:50px" width="120" src="{{ asset('custom_files/bo_files/dummy-passport-image.jpg') }}"><br>
								<span>Nominee/Heir 2</span>
								</span>
								<span>
									<img style="border:1px solid #ddd;margin-left:50px" width="120" src="{{ asset('custom_files/bo_files/dummy-passport-image.jpg') }}"><br>
								<span>Guardian 1</span>
								</span>
								<span>
									<img style="border:1px solid #ddd;margin-left:50px" width="120" src="{{ asset('custom_files/bo_files/dummy-passport-image.jpg') }}"><br>
								<span>Guardian 2</span>
								</span>
						</p>
						<!-- <p class="p8-ln-15">
						</p> -->
						
						<table border="1" width="100" class="p8-ln-16">
						  <tr>
							<th></th>
							<th>Name</th>
							<th>Signature</th>
						  </tr>
						  <tr>
							<td>Nominee/Heir 1</td>
							<td>{{ $get_data->nominee_first_name . " " . $get_data->nominee_middle_name . " " . $get_data->nominee_first_name }}</td>
							<td>
								@if($get_data->nominee_signature)
									<img style="margin-left:100px" width="30" src="{{ asset('custom_files/bo_files/'.$get_data->nominee_signature) }}"><br>
								@endif
							</td>
						  </tr>
						  <tr>
							<td>Guardian 1</td>
							<td></td>
							<td></td>
						  </tr>
						  <tr>
							<td>Nominee/Heir 2</td>
							<td></td>
							<td></td>
						  </tr>
						  <tr>
							<td>Guardian 2</td>
							<td></td>
							<td></td>
						  </tr>
						  <tr>
							<td>First Account Holder</td>
							<td>{{ $get_data->name_of_first_holder }}</td>
							<td>
								@if($get_data->signature)
									<img style="margin-left:100px" width="30" src="{{ asset('custom_files/bo_files/'.$get_data->signature) }}"><br>
								@endif
							</td>
						  </tr>
						  <tr>
							<td>Second Account Holder</td>
							<td>{{ $get_data->second_holder_short_name . " " . $get_data->nominee_middle_name . " " . $get_data->nominee_first_name }}</td>
							<td>
						   		<?php if($get_data->bo_type='Joint Account') : ?>
									@if($get_data->second_holder_signature)
										<img style="margin-left:100px" width="30" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_signature) }}"><br>
									@endif
								<?php endif; ?>
							</td>
						  </tr>
						</table>
				
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- page 9 -->
	<div class="lik-uftcl-main-body" style="height:1160px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
					<div class="lik-uftcl-pdf-body">
						<h3 class="p9-ln-1">
							<span>Power of Attorney (POA) Form</span>
						</h3>
					
						<p class="p9-ln-2">
								<span>Please complete all details in CAPITAL Letters.Please fill all names correctly.All communaications shall be sent to the correspondence address of only the First Named Account Holder as spedified in BO Account Opening Form 02.
								</span>
						</p>
						<p class="p9-ln-3">
									<span>Application No. 
										<input type="text">
										
									</span>	
									<span>Date:(DD/MM/YYYY) 
										<input type="text">			
									</span>	
						</p>
						<div class="main">
							<p class="p9-ln-4">
									<span>Name of CDBL Participant (up to Character)</span>
									<span>CDBL Participant ID</span>
							</p>
							<p class="p9-ln-5">
									<span>UNITED FINANCIAL TRADING COMPANY LIMITED</span>
									<span>
										<input type = "text">
									</span>
							</p>
							<p class="p9-ln-6">
									<span>Account Hotder's Bo ID
											<input type = "text">
									</span>
							</p>
							<p class="p9-ln-7">
									<span>Name of Account Holder (lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)
									</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p9-ln-8">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
						</div>
						<div class="main">
							<p class="p9-ln-8">
									<span>Power of Attorney Holder's Details</span>
							</p>
							<p class="p9-ln-9">
									<span>Name in full 
										<input type="text">
									</span>
							</p>
							
							<p class="p9-ln-10">
									<span>Short name of nominee(lnsert full name starting with Title i.e. MrJMrs"/Ms/Dr. abbreviate only if over 30 characters)</span>
									<span>Title i.e. Mr/Mrs/Ms/Dr.</span>
							</p>
							<p class="p9-ln-11">
									<span><input type = "text"></span>
									<span><input type = "text"></span>
							</p>
						</div>
							<p class="p9-ln-12">
									<span>1.Power of Attorney Holder's contact Details</span>
							</p>
						<div class="main">
							<p class="p9-ln-13">
									<span>Address
										<input type = "text"></span>
							</p>
							<p class="p9-ln-14">
									<span>City
										<input type = "text"></span>
									<span>Post Code
										<input type = "text"></span>
									<span>State/Division
										<input type = "text"></span>
									<span>Country
										<input type = "text"></span>
									<span>Telephone
										<input type = "text"></span>
							</p>
							<p class="p9-ln-15">
									<span>Mobile Phone
										<input type = "text">
									</span>
									<span>Fax
										<input type = "text">
									</span>
									<span>Email
										<input type = "text">
									</span>
							</p>
						</div>
							<p class="p9-ln-16">
									<span>2.Power of Attorney Holder's contact Nationality and passport</span>
							</p>
						<div class="main">
							<p class="p9-ln-17">
									<span>National lD No. :
										<input type = "text">
									</span>
							</p>
							<p class="p9-ln-18">
									<span>Passport No.
										<input type = "text">
									</span>
									<span>Issue Place
										<input type = "text">
									</span>
									<span>Issue Date
										<input type = "text">
									</span>
									<span>Expiry Date
										<input type = "text">
									</span>
							</p>
						</div>
							<p class="p9-ln-19">
									<span>3.Others information of power of Attorney Holder</span>
							</p>
						<div class="main">
							<p class="p9-ln-20">
									<span>Residency:</span>
									<span>Resident
										<input type="checkbox">
									</span>
									<span>Non Resident
										<input type="checkbox">
									</span>
									<span>Nailonatity
										<input type = "text">
									</span>
									<span>Dareof Birth(DD/MM/yyyy)
										<input type = "text">
									</span>
							</p>
							<p class="p9-ln-21" >
									<span>Power of Attorney Effecctive From
										<input type = "text">
									</span>
									<span>To
										<input type = "text">
									</span>
									<span>
										<span>D D M M Y Y Y Y </span>
										<span>D D M M Y Y Y Y</span>									
									</span>
							</p>
							
							<div class="main-2">
								<p class="p9-ln-22">
									<span>Remarks (lnsert reference to POA document i.e. POA or General POA etc.) :
										<input type="text">
									</span>
									<span>
										<input type="text">									
									</span>
									<span>
										<input type="text">
									</span>
								</p>
								
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- page 10 -->
	<div class="lik-uftcl-main-body" style="height:1160px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
					<div class="lik-uftcl-pdf-body">
					
						<p class="p10-ln-1">
							<span>4.Photograph of Power of Attorney Holder</span>
						</p>
						<div class="p10-ln-2">
							<span>
								<img style="border:1px solid #ddd;margin-left:550px;margin-top: 50px" width="220" src="{{ asset('custom_files/bo_files/dummy-passport-image.jpg') }}"><br>
							</span>
							<p>(POA Holder)</p>
						</div>					
						<p class="p10-ln-3">
							<span>5.Declaration</span>
						</p>
						<p class="p10-ln-4">
							<span>The rules and regulations of the Depository and CDBL Partcipant pertaining to an account which are in force now have been read by me/us and l/we have understood the same and l/we agree to abide by and to be bound by the rules as are in force from time to time for such accounts. l/we also declare that the particulars given by me/us are ture to the best of my/our knowledge as on the date of making such application. l/we further agree that any false/misleading information given by me/us suppression of any material fact will render my/our account liable for termination and further action.
							</span>
						</p>
						
						<table class="p10-ln-5" border="1">
							  <tr>
								<th>Applicant</th>
								<th>Name of applicanUAuthorized Signatories in case of Ltd.Co.</th>
								<th>Signature</th>
							  </tr>
							  <tr>
								<td>POA Holder</td>
								<td></td>
								<td></td>
							  </tr>
							  <tr>
								<td>First Applicant</td>
								<td></td>
								<td></td>
							  </tr>
							  <tr>
								<td>Second Applicant</td>
								<td></td>
								<td></td>
							  </tr>
							  <tr>
								<td>3rd Signatory
								    <br/>
								    (Ltd.Co. Only)</td>
								<td></td>
								<td></td>
							  </tr>
						</table>
					
					</div>			
				</div>
			</form>
		</div>
	</div>

	<!-- page 11 -->
	<div class="lik-uftcl-main-body" style="height:1160px;">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-body">
					
					<p class="p11-ln-1">
						<span>Central Depository Bangladesh Limited (CDBL)</span>
						<span>Depository Account (BO Account) opened with GDBL Participant</span>
						<span>Terms & Conditions - Bye Laws 7.3.3 (c)</span>
					</p>
					<p class="p11-ln-2">
						<span><b>UNITED FINANCIAL TRADING COMPANY LIMITED</b></span>						
						<span>TREC Holder Dhaka & Chittagong Stock Exchange Ltd.</span>						
						<span>TREC No.- DSE:227 & CSE : 043</span>						
						<span>Full Service Depository Participant Of CDBL , DP No. 31100</span>						
					</p>
					<p class="p11-ln-3">
						<span>Dear Sir,</span><span style="font-size:12px;line-height:16px;">
						Please open a Depository account (BO Account) in my/our name (s) on the terms and conditions set out bellow. ln consideration of <strong> UNITED FINANCIAL TRADING COMPANY LIMITED </strong> (the'CDBL Participant" opening the account providing depository account facilities to me/us, l/we have signed the BO Account Opening Form as a token acceptance of the terms and conditions set out below.
						</span>
					</p>
					<ol class="p11-ln-list">
						<li style="font-size:12px;line-height:16px;">I/we agree to be bound by The Depositories Act 1999. Depositories Regulations 2000 The Depository (User) Requlations 2003 and abide by the Bye Laws and operatiny lnstructions issued From time to time by CDBL.
						</li>
					
						<li style="font-size:12px;line-height:16px;">CDBL shall allocate a unique identification number to me/us (Account Holder BO lD) for the CDBL Participant to maintain a separate Account for me/us unless the l/we instructs the CDBL Participant to keep the securities in an Omnibus Account of the CDBL Participant. The CDBL Participant shall however ensure that my/our securities shall not be mixed with the CDBL Participant's own securities.
						</li>
					
						<li style="font-size:12px;line-height:16px;">l/we agree to pay such fees, charges and deposits to the CDBL Participant, as may be mutually agreed upon for the purpose of opening and maintaining my/our account, for carrying out the instructions and for rendering such other services as are incidental or consequential to mylour holding securities in and transacting through the said depository account with the CDBL Participant.
						</li>
					
						<li>l/we shall be responsible for:
					
							<ol class="p11-ln-list-1">
								<li style="font-size:12px;line-height:16px;"> The veracity of all statements and particulars set out in the account opening form, supporting or accompanying documents.</li>
								<li style="font-size:12px;line-height:16px;"> The authenticity and genuinenss of all certificates and/or documents submitted to the CDBL Participant along with or in support of the account opening form or subsequently for dematerialization.</li>
								<li style="font-size:12px;line-height:16px;"> Title to the Securities submitted to the CDBL Participant from time to time depaterialization.</li>
								<li style="font-size:12px;line-height:16px;"> Ensuring at all times that the securities to the credit of my/our account are sufficient to meet the instructions issued to the CDBL Participant for effecting any transaction/transfer.</li>
								<li style="font-size:12px;line-height:16px;"> lnforming the CDBL Participant at the earliest of any changes in my/our account particulars such as address, bank details, status, authorizations, mandates, nomination, signature etc.</li>
								<li style="font-size:12px;line-height:16px;"> Furnishing accurate identification details whilst subscribing to any issue of securities.</li>
							</ol>
						</li>
					
					
					
						<li style="font-size:12px;line-height:16px;">l/we shall notify the CDBL Participant of change in the particulars set out in the application form submitted to the CDBL Participant at the time of opening the account or furnished to the CDBL Participant from time to time at the earliest. The CDBL Participant shall not be liable or responsible for any loss that may be caused to me/us by reason of my/our failure to intimate such change to the CDBL Participant at the earliest
						</li>
					
						<li style="font-size:12px;line-height:16px;">l/we have executed at BO Account Nomination form: 
					
							<ol class="p11-ln-list-1">
								<li style="font-size:12px;line-height:16px;"> ln the event of my/our death, the nominee shall receive/draw the securities held in my/our account.</li>
								<li style="font-size:12px;line-height:16px;"> ln the event, the nominee so authorlzed remains a minor at the time of my/our death the legal guardian is authorized to receive/draw the securities held in my/our account.</li>
								<li style="font-size:12px;line-height:16px;"> The nominee so authorized, shall be entitled to all my/our account to the exclusion of all other persons i.e. my/our heirs, executors and administrators and all other persons claiming through or under me/us and delivery of securities to the nominee in pursuance of this authority shall be binding on all other persons.</li>
							</ol>
						</li>
					
					
						<li style="font-size:12px;line-height:16px;"> l/we may at any time call upon the CDBL Participant to close my/our account with the CDBL Participant provided no instructions remain pending or unexecuted and no fees or charges remain payable by me/us to the CDBL Participant. ln such event l/we may close my/our account by executing the Accounting Closing From if no balances are standing to my/our credit in the account. ln case any balances of securities exist in the account the account may be closed by me/us in one of the following ways:
						
					
							<ol class="p11-ln-list-1">
								<li style="font-size:12px;line-height:16px;"> By dematerialization of all existing balance in my/our account.</li>
								<li style="font-size:12px;line-height:16px;"> By transfer of all existing balances in my/our account to one or more of my/our other account(s) held with any other CDBL Participant(s).</li>
								<li style="font-size:12px;line-height:16px;"> By rematerialization of a part of the existing balances in my/our account by transferring the rest to one or more of my/our other account(s) with any other CDtsL Participant(s).</li>
							</ol>	
						</li>
					</ol>		
					
							
				</div>
			</form>
		</div>
	</div>

	<!-- page 12 -->
	<div class="lik-uftcl-main-body" style="height:1160px;margin-top:30px">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
					<div class="lik-uftcl-pdf-body">
					
					<ol class="p12-ln-list">
						<li style="padding: 0;	list-style: none;"></li>
						<li style="padding: 0;	list-style: none;"></li>
						<li style="padding: 0;	list-style: none;"></li>
						<li style="padding: 0;	list-style: none;"></li>
						<li style="padding: 0;	list-style: none;"></li>
						<li style="padding: 0;	list-style: none;"></li>
						<li style="padding: 0;	list-style: none;"></li>
						<li>CDBL Participant convenants that it shall.
							<ol class="p12-ln-list-1">
								<li> Act only on the instructions or mandate of the account Holder or that of such person(s) as may have been duly authorized by the Account Holder in that behalf.
								</li>
							
								<li> Not effect any debit or credit to and from the account of the Account Holder without appropriate instructions from the Account Holder.
								</li>
							
								<li> Maintain adequate audit trail of the execution of the instructions of the Account Holder.</li>
							
								<li> Not honour or act upon any instructions for effecting any debit to the account of the Account Holder in respect of any securities unless.
						
									<ol class="p12-ln-list-2">
										<li>Such instructions are issued by the Account Holder under his signature of that of his/its constituted attorney duly authorized in that behalf.
										</li>
									
										<li>The CDBL Participant is satisfied that the signature of the Account Holder under which instructions are issued matches with the specimen of the Account Holder or his/its constituted attorney available on the records of the CD Participants.
										</li>
									
										<li>The balance of clear securities available in the account holder's are sufficient to honour the account Holder's instructions.
										</li>
									</ol>
								</li>
					
								<li>(e) Furnish to the account holder a statement of account at the end of every month if there has been even a single entry or transaction during that month, and in any event once at the end of each financial year. The CDBL Participant shall furnish such statements at such shorter periods as may be required by the Account Holder.on payment of such charges by the Account Holder as may be specified by the CDBL Participant. The Account Holder shall scrutinize every statement of account received from the CDBL Participant for the accuracy and veracity thereof and shall promptly bring to the notice of the CDBL Participant any mistakes inaccuracies or discrepancies in such statements.
								</li>
							
								<li>(f) Promptly attend to all grievances/complaints of the Account Holder and shall resolve all such grievances/ complaints as it relate to matters exclusively within the domain of the CDBL Participant within one month of the same being brought to the notice of the CDBL Participant and shall forthwith forward to and follow up with CDBL all other grievances/complaints of the Account Holder on the same being brought to the notice of the CDBL Participant and shall endeavour to resolve the same at the earliest.
								</li>
							</ol>
						</li>
					
						<li>The CDBL Participant shall be entitled to terminate the account relationship in the event of the Account Holder.
						
					
							<ol class="p12-ln-list">
								<li>Failing to pay the fees or charges as may be mutually agreed upon within a period of one month from the date of demand made in that behalf.</li>
							
								<li>Submitting for dematerialaization any certificates or other documents of title which forged fabricated, counterfeit or stolen or have been obtained by forgery or the transfer whereof is restrained or prohibited by any direction, order or decree of any court or the Securities and Exchange Commission.</li>
							
								<li>Commits or Participates in any fraud or other act of moral turpitude in his/its dealing with the CDBL Participant.</li>
							
								<li>Otherwise misconduct's himself in any manner.</li>
							</ol>
						</li>
						<li>Declaration and signature l/we hereby acknowledge that liwe have read and understood the aforesaid terms and conditions for operating Depository Account (BO Account) with CDBL Participant and agree to comply with them.</li>
					</ol>
					
					<table class="p12-ln-1" border="1">
						  <tr>
							<th>Applicant</th>
							<th>Name of applicant/Authorized Signatories in case of Ltd.Co.</th>
							<th>Signature</th>
						  </tr>
						  <tr>
							<td>First Applicant</td>
							<td>
								{{ $get_data->bo_title .' '. $get_data->name_of_first_holder .' '. $get_data->bo_middle_name .' '. $get_data->bo_last_name. ' ' . $get_data->bo_short_name }}
							</td>
							<td>
								@if($get_data->signature)
									<img style="margin-left:100px" width="70" src="{{ asset('custom_files/bo_files/'.$get_data->signature) }}"><br>
								@endif
							</td>
						  </tr>
						  <tr>
							<td>Second Applicant</td>
							<td>
								{{ $get_data->second_holder_title .' '. $get_data->second_joint_holder .' '. $get_data->second_holder_middle_name .' '. $get_data->second_holder_last_name. ' ' . $get_data->second_holder_short_name }}
							</td>
							<td>
						   		<?php if($get_data->bo_type='Joint Account') : ?>
									@if($get_data->second_holder_signature)
										<img style="margin-left:100px" width="70" src="{{ asset('custom_files/bo_files/'.$get_data->second_holder_signature) }}"><br>
									@endif
								<?php endif; ?>
							</td>
						  </tr>
						  <tr>
							<td>Third Applicant</td>
							<td>
								{{ $get_data->third_holder_title .' '. $get_data->third_joint_holder .' '. $get_data->third_holder_middle_name .' '. $get_data->third_holder_last_name. ' ' . $get_data->third_holder_short_name }}
							</td>
							<td>
						   		<?php if($get_data->bo_type='Joint Account') : ?>
									@if($get_data->third_holder_signature)
										<img style="margin-left:100px" width="70" src="{{ asset('custom_files/bo_files/'.$get_data->third_holder_signature) }}"><br>
									@endif
								<?php endif; ?>
							</td>
						  </tr>
					</table>
					
					</div>			
				</div>
			</form>
		</div>
	</div>

	<!-- page 13 -->
	<div class="lik-uftcl-main-body" style="height:1160px;margin-top:60px">
		<div class="lik-uftcl-print-body">
			<form>
				<div class="lik-uftcl-pdf-header"></div>
					<div class="lik-uftcl-pdf-body">
					
							<h1 class="kyc-header">UNITED FINANCIAL TRADING COMPANY LIMITED</h1>
							<h2 class="kyc-header-2"><span> KYC Profile Form</span></h2>
							<h3 class="kyc-header-3"><span> Write in Block Letter</span></h3>
							
							<p class="p13-ln-1">
								<span>1.Customer's Name </span>
								<span>:</span>
								<span>
									<input type = "text" value="{{ $get_data->bo_title .' '. $get_data->name_of_first_holder .' '. $get_data->bo_middle_name .' '. $get_data->bo_last_name. ' ' . $get_data->bo_short_name }}">
								</span>
							</p>
							<p class="p13-ln-2">
								<span>2. Father/Husband's Name </span>
								<span>:</span>
								<span>
									<input type = "text" value="{{ $get_data->father_or_husband_name }}">
								</span>
							</p>
							<p class="p13-ln-3">
								<span>3. Mother's Name </span>
								<span>:</span>
								<span>
									<input type = "text" value="{{ $get_data->mother_name }}">
								</span>
							</p>
							<p class="p13-ln-4">
								<span>4. Present Address </span>
								<span>:</span>
								<span>
									<input type = "text" value="{{ $get_data->address_1 }}">
								</span>
							</p>
							<p class="p13-ln-5">
								<span class="blank"></span>
								<span>:</span>
								<span>
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-6">
								<span>5. Permanent Address </span>
								<span>:</span>
								<span>Thana/Upazila
									<input type = "text">
								</span>
								<span>Union
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-7">
								<span></span>
								<span>:</span>
								<span>District/City
									<input type = "text" value="{{ $get_data->city }}">
								</span>
								<span>Division
									<input type = "text" value="{{ $get_data->state }}">
								</span>
								<span>Post Code
									<input type = "text" value="{{ $get_data->postal_code }}">
								</span>
							</p>
							<p class="p13-ln-8">
								<span>6. Specify the proof of indentity submitted</span>
							</p>
							<p class="p13-ln-9">
								<span>7.Address Verified by</span>
								<span>:</span>
								<span>Name
									<input type = "text">
								</span>
								<span>Signature
									<input type = "text">
								</span>	
							</p>
							<p class="p13-ln-10">
								<span>8.Occupasion</span>
								<span>:</span>
								<span>Service
									<input type="checkbox">
								</span>
								<span>Business
									<input type="checkbox">
								</span>
								<span>Student
									<input type ="checkbox">
								</span>
								<span>Others
									<input type ="checkbox">
								</span>
							</p>
							<p class="p13-ln-11">
								<span>9.Office/Work/Educational Institution/Owner's Name and Address </span>
								<span>:</span>
								<span>
									<input type = "text">
								</span>
								<span>
									<input type = "text">
								</span>

							</p>
							<p class="p13-ln-12">
								<span></span>
								<span></span>
								<span>Designation
									<input type = "text">
								</span>
								<span>Phone/Mobile
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-13">
								<span>10.Date of Birth </span>
								<span>:</span>
								<span>
									<input type = "text"  value="{{ $get_data->date_of_birth }}">
								</span>
							</p>
							<p class="p13-ln-14">
								<span>11.Mobile Number </span>
								<span>:</span>
								<span>
									<input type = "text" value="{{ $get_data->phone_number }}">
								</span>
							</p>
							<p class="p13-ln-15">
								<span>12.Gender </span>
								<span>:</span>
								<span>Male
									<input type="checkbox" {{ $get_data->sex_code=="Male" ? "checked" : "" }}>
								</span>
								<span>Female
									<input type="checkbox" {{ $get_data->sex_code=="Female" ? "checked" : "" }}>
								</span>
							</p>
							<p class="p13-ln-16">
								<span>13.ID Type </span>
								<span>:</span>
								<span>National ID
									<input type="checkbox" {{ ($get_data->first_holder_national_id) ? "checked" : "" }}>
								</span>
								<span>Passport
									<input type="checkbox" {{ ($get_data->passport_number) ? "checked" : "" }}>
								</span>
								<span>Driving License
									<input type="checkbox">
								</span>
								<span>Others
									<input type="checkbox">
								</span>
							</p>
							<p class="p13-ln-17">
								<span>14. National ID Nmuber </span>
								<span>:</span>
								<span>
									<input type = "text" value="{{ $get_data->first_holder_national_id }}">
								</span>
							</p>
							<p class="p13-ln-18">
								<span>15.Bank Account Details </span>
								<span>:</span>
								<span>Bank Name
									<input type = "text" value="{{ $get_data->bank_name }}">
								</span>
								<span>Branch
									<input type = "text" value="{{ $get_data->bank_branch_name }}">
								</span>	
							</p>
							<p class="p13-ln-19">
								<span></span>
								<span></span>
								<span>Account No.
									<input type = "text" value="{{ $get_data->bank_account_number }}">
								</span>
							</p>
							<p class="p13-ln-20">
								<span>16.Introducer Information </span>
								<span>:</span>
								<span>Name
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-21">
								<span></span>
								<span>:</span>
								<span>Address
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-22">
								<span></span>
								<span></span>
								<span>Phone/Mobile No.
									<input type = "text">
								</span>
								<span>T&T
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-23">
								<span></span>
								<span></span>
								<span>Relation
									<input type = "text">
								</span>
								<span>UFTCL Code
									<input type = "text">
								</span>
							</p>
							<p class="p13-ln-24">
								<span>17.Whether the address of the account holder is/are verified</span>
								<span>:</span>
								<span>Yes
									<input type="checkbox">
								</span>
								<span>No
									<input type="checkbox">
								</span>
							</p>
							<p class="p13-ln-25">
								<span>18.lf reply is positive then mention the way of the verification</span>
								<span>:</span>
								<span>By Courier
									<input type="checkbox">
								</span>
								<span>By Personal
									<input type="checkbox">
								</span>
							</p>
							<p class="p13-ln-26">
								<span>
									<img width="60" style="margin-left:120px" src="{{ asset('custom_files/bo_files/'.$get_data->signature) }}">
									<span>Customer Signature</span>
								</span>
								<span>
									<img width="40" style="margin-left:120px;" src="{{ asset('custom_files/blank_image.png') }}">
									<span>Delear/Associate Signature</span>
								</span>
								<span>
										<img width="40" style="margin-left:120px" src="{{ asset('custom_files/blank_image.png') }}">
									<span>Introducer Signature</span>
								</span>
							</p>
					
						<!-- <div class="main">
							<p class="p13-ln-27">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</p>
						</div> -->
						<table class="p13-ln-27" border="1">
							<tr>
								<td>Office Use only :</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>							
								<td></td>
								<td><span>DP</span></td>
								<td><span>Branch Incharge</span></td>
								<td><span>Incharge</span></td>
							</tr>
						</table>
						
					</div>			
				</div>
			</form>
		</div>
	</div>

	<div class="lik-uftcl-main-body" style="height:auto;">
		<div class="lik-uftcl-print-body" style="height:auto;">
			<h2>First Holder Passport</h2>

			<?php if($get_data->first_holder_passport_file) : ?>
				<img width="100%" src="{{ asset('custom_files/bo_files/'.$get_data->first_holder_passport_file) }}">
			<?php endif; ?>

			<h2>First Holder NID</h2>
			<?php if($get_data->first_holder_national_id_file) : ?>
				<img width="100%" src="{{ asset('custom_files/bo_files/'.$get_data->first_holder_national_id_file) }}">
			<?php endif; ?>

			<h2>First Holder TIN</h2>
			<?php if($get_data->tin) : ?>
				<img width="100%" src="{{ asset('custom_files/bo_files/'.$get_data->tin) }}">
			<?php endif; ?>

			<h2>Nominee NID File</h2>
			<?php if($get_data->nominee_nid_file) : ?>
				<img width="100%" src="{{ asset('custom_files/bo_files/'.$get_data->nominee_nid_file) }}">
			<?php endif; ?>

			<h2>Nominee Passport File</h2>
			<?php if($get_data->nominee_passport_file) : ?>
				<img width="100%" src="{{ asset('custom_files/bo_files/'.$get_data->nominee_passport_file) }}">
			<?php endif; ?>

		</div>
	</div>



</body>
</html>