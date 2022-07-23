<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\MKISTAT;
use App\MKISTAT_ARCHIVE;
use App\IDX;
use App\MAN;
use App\Event;
use App\Product;
use App\CircuitBreaker;
use App\Settings;
use App\IPO;
use App\Mail\EmailVerification;
use App\Mail\myTestMail;
use App\Mail\PasswordReset;
use App\MarketCommentry;
use App\EquityResearch;
use App\EconomicUpdate;
use App\UserBOAccountData;
use App\OrderManagement;
use App\WithdrawRequest;
use App\ClientLimits;
use App\Organisation;
use App\Http\Controllers\Controller;
use App\IndustryData;
use App\Subscribers;
use App\Notice;
use Auth;
use Mail;
use Hash;
use Response;
use Validator;
use DateTime;
use App\Contact;
use App\User;
use App\PasswordPolicy;
use App\Webcontent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\WatchList;
use App\BatchData;
use App\Group;
use App\Kiosk;
use Session;
session_start();

class KioskController extends Controller
{
    public function kiosk_login() {
        return view('kiosk.login');
    }
    
    public function kiosk(Request $request) {
        $email = $request->email;
        $pass = $request->password;
        $data = DB::select('select * from users where email="'.$email.'"');


        if(Hash::check($pass, $data[0]->password)) {
             $userCount = 1;

             $timeOutSeconds = 40;
             
             //$data['timeoutSeconds'] =$timeOutSeconds;
             // print_r($data['timeoutSeconds']);exit();
            //$_SESSION["Auth"] = $data;
            Session::set('kAuth', $data);
            Session::set('kAuth.timeOutSeconds', $timeOutSeconds);
            // print_r (Session::get('kAuth'));exit();
            return redirect('/kiosk_user_panel');
        }
        return redirect('/kiosk_login')->with('flash_massage', 'Invalid user email or password!');
    }

    public function kiosk_user_panel() {
        $data = [];
        $ds30 = DB::select("SELECT IDX_CAPITAL_VALUE capital_value,  IDX_DATE_TIME idx_time FROM IDX WHERE IDX_INDEX_ID='DS30' ORDER BY IDX_DATE_TIME");
        $data['ds30'] = $ds30;
        $data['ds30_min'] = DB::select("SELECT MIN(IDX_CAPITAL_VALUE) AS MIN_VAL FROM IDX WHERE IDX_INDEX_ID='DS30'");
        $data['dses_min'] = DB::select("SELECT MIN(IDX_CAPITAL_VALUE) AS DSES_MIN_VAL FROM IDX WHERE IDX_INDEX_ID='DSES'");
        $data['dsex_min'] = DB::select("SELECT MIN(IDX_CAPITAL_VALUE) AS DSEX_MIN_VAL FROM IDX WHERE IDX_INDEX_ID='DSEX'");

        // $client_code = Auth::User()->client_code;
        // $ipo_query = UserBOAccountData::where('dp_internal_reference_number', $client_code)->first(['ipo_apply']);
        // $data['ipo_query'] = $ipo_query->ipo_apply;
        $data['com_code'] = DB::select("SELECT * FROM INDUSTRY_DATA");
        $data['get_record'] = MAN::limit(30)->orderBy('MAN_ANNOUNCEMENT_DATE_TIME', 'desc')->get();
        return view('kiosk/dashboard', $data);
    }


    public function kiosk_client_profit_loss() {
        $data = [];
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $data['api_token'] = 'Bearer '.$api_token[0]->api_token;
        return view('kiosk/kiosk_client_profit_loss', $data);
    }

    public function print_kiosk_client_profit_loss($from_date, $to_date) {

        $data = [];
        $data['company_info'] = Settings::get()->all();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $api_token = 'Bearer '.$api_token[0]->api_token;

        // $from_date = date("d F Y", strtotime($_POST['from_date']));
        // $to_date = date("d F Y", strtotime($_POST['to_date']));

        $client_code = session()->get( 'kAuth')[0]->client_code;

        $from_date1 = date("Y-m-d", strtotime($from_date));
        $to_date1 = date("Y-m-d", strtotime($to_date));

        $curl = curl_init();

        curl_setopt_array($curl, array(
          //CURLOPT_URL => "http://118.179.212.148:81/api/ProfitAndLoss/2019-06-01/2019-07-02/3663/0",
          CURLOPT_URL => "http://127.0.0.1:5000/api/ProfitAndLoss/$from_date1/$to_date1/$client_code/0",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            // "accept: application/json",(eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJhZG1pbiIsImVtYWlsIjoiaW5mb0BjeWdudXNpbm5vdmF0aW9uLmNvbSIsIkpvaW5EYXRlIjoiMjAxOS0wMi0xMyIsImp0aSI6ImNkY2YyM2RiLWYwM2ItNDllNi04ODg1LWZiN2NhNzgwZjZjOSIsImV4cCI6MTU5NjUyMzM5MywiaXNzIjoiZnVhZEBjeWdudXNpbm5vdmF0aW9uLmNvbSIsImF1ZCI6ImZ1YWRAY3lnbnVzaW5ub3ZhdGlvbi5jb20ifQ.3fk3L7Xy_WwM6sLuwDVK7EBwabblPE99TSv6LibAibs)
            "Authorization: $api_token",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $data['get_data'] = json_decode($response);
        // print_r($data['get_data']);exit();

        return view('kiosk/print_kiosk_copy_client_profite_loss', $data);
    }


/*********************** New Portfolio Position **********************/
    public function kiosk_user_new_portfolio_position() {
        $data = [];
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $data['api_token'] = 'Bearer '.$api_token[0]->api_token;
        return view('kiosk/kiosk_user_new_protfolio_position', $data);
    }

    public function print_kiosk_user_new_portfolio_position() {
        $company_info = Settings::get()->all();//new

        $data = [];

        $data['company_info'] = $company_info; //new
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $data['api_token'] = 'Bearer '.$api_token[0]->api_token;
        $client_code = session()->get( 'kAuth')[0]->client_code;
        
        return view('kiosk/print_kiosk_user_new_portfolio_position', $data);
    }
/*********************************************************************/ 
    public function kiosk_client_instrument_costing(Request $request) {
        $data = [];
        $action = Input::get('submit');
        $data['get_data'] = array();
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $data['client_code'] = "";
        $data['date'] = "";
        $data['instrument_id'] = "";
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $api_token = 'Bearer '.$api_token[0]->api_token;
        //$instrument_data = file_get_contents("https://api.ucbcapital.com/ucbapi/instrument.php");
        //$data['instrument_data'] = json_decode($instrument_data);
        //$data_info = json_decode($instrument_data);
        $data_info = MKISTAT::all();
        $data['instrument_data'] = MKISTAT::all();
        // dd($data['instrument_data']);
        $itemCode = [];
        foreach ($data_info as $value) {
          $itemCode[] = $value->MKISTAT_INSTRUMENT_CODE;
        }

        $data['InstrumentCode'] = json_encode($itemCode);
        if($action == "Submit") {
            $instrument_id = $request->instrument_id;
            $date = date("d M Y", strtotime($request->date));
            $client_code = session()->get( 'kAuth')[0]->client_code;
            
             $date2 = date("Y-m-d");
             //echo $instrument_id."<pre>".$client_code."<pre>".$date2;die();
             $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://127.0.0.1:5000/api/InstrumentCosting/$client_code/$date2/$instrument_id",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                // "accept: application/json",
                "Authorization: $api_token",
                "content-type: application/json"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $data['get_data'] = json_decode($response);

            //dd($data['get_data']);exit();

            //$url = "https://api.ucbcapital.com/ucbapi/test_2.php?client_code=". $client_code . '&instrument_id='.$instrument_id;
            //$papana = file_get_contents($url);
            //$data['get_data'] = json_decode($papana);
            // dd($papana);exit();
            $data['client_code'] = $client_code;
            $data['date'] = $request->date;
            $data['instrument_id'] = $instrument_id;
        }

        //dd($data);
        return view('kiosk/kiosk_client_instrument_costing', $data);
    }

    public function print_kiosk_client_instrument_costing($date, $instrument_id) {
        $data = [];
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $data['company_info'] = Settings::get()->all();
        $date = date("d M Y", strtotime($date));
        $data['bo_id'] = $client_code;
        $data['name'] = 'Kader Khan';
        $data['instrument_id'] = $instrument_id;
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $data['api_token'] = 'Bearer '.$api_token[0]->api_token;
        return view('kiosk/print_kiosk_client_instrument_costing', $data);
    }

    public function kiosk_client_ledger_statement(Request $request) {
        $data = [];
        $action = Input::get('submit');
        $data['get_data'] = array();
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $data['client_code'] = $client_code;

        $data['from_date'] = "";
        $data['to_date'] = "";
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $data['api_token'] = 'Bearer '.$api_token[0]->api_token;
        if($action == "Submit") {
            $instrument_id = $request->instrument_id;
            $from_date = date("d M Y", strtotime($request->from_date));
            $to_date = date("d M Y", strtotime($request->to_date));
            
            // $url = "http://123.0.17.7/api/sptest_7.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
            // $url = "http://123.0.17.7/api/test_4.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
            // $papana = file_get_contents($url);
            // $data['get_data'] = json_decode($papana);
            $data['from_date'] = date("Y-m-d", strtotime($request->from_date));
            $data['to_date'] = date("Y-m-d", strtotime($request->to_date));
            /*echo "<pre>";
            print_r(count($data['get_data']));
            echo "</pre>";
            die;*/
        }
        return view('kiosk/kiosk_client_ledger_statment', $data);
    }

    public function print_kiosk_client_ledger_statement($from_date, $to_date) {

        $data = [];
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $data['company_info'] = Settings::get()->all();
        $from_date = date("Y-m-d", strtotime($from_date));
        $to_date = date("Y-m-d", strtotime($to_date));
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $api_token = 'Bearer '.$api_token[0]->api_token;
        // $from_date = date("d M Y", strtotime($from_date));
        // $to_date = date("d M Y", strtotime($to_date));
        // $url = "http://123.0.17.7/api/sptest_7.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
        // $papana = file_get_contents($url);
        // $data['get_data'] = json_decode($papana);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://127.0.0.1:5000/api/LedgerSummary/$from_date/$to_date/$client_code",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            // "accept: application/json",
            "Authorization: $api_token",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        //$get_data = json_decode($response);
        $data['get_data'] = json_decode($response);
        // print_r($data['get_data']);
        //  exit();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['client_code'] = $client_code;
        // dd($data['get_data']);
       
        //return view('user_panel/print_client_ledger_statement', $data);
       return view('kiosk/print_kiosk_copy_ledger_statement', $data);

    }

    public function kiosk_client_confirmation_note(Request $request) {
        $data = [];
        $data['from_date'] = "";
        $data['to_date'] = "";
        $action = Input::get('submit');
        $data['get_data'] = array();
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $data['client_code'] = $client_code;
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $api_token = 'Bearer '.$api_token[0]->api_token;
        if($action == "Submit") {
            $instrument_id = $request->instrument_id;
            // $from_date = date("d M Y", strtotime($request->from_date));
            // $to_date = date("d M Y", strtotime($request->from_date));
            $from_date = date("Y-m-d", strtotime($request->from_date));

            $to_date = $from_date;
            // $to_date = date("Y-m-d", strtotime($to_date));
            // echo $client_code;
            $curl = curl_init();
            $data2 = array(
                "exchangeID"=> "0",
                "investorCode"=> $client_code,
                "fromDate"=> $from_date,
                "toDate"=> $to_date
            );
            $data2 = json_encode($data2);  

            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://127.0.0.1:5000/api/ConfirmationNoteSummary",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_HTTPHEADER => array(
                // "accept: application/json",
                "Authorization: $api_token",
                "content-type: application/json",
                'Content-Length: ' . strlen($data2) 
              ),
              CURLOPT_POSTFIELDS => $data2,
            ));

            $response = curl_exec($curl);
            // echo $response;exit();
            $err = curl_error($curl);

            curl_close($curl);

            $data['get_data'] = json_decode($response);
            //dd($data['get_data']);exit();

            // echo $url = "http://123.0.17.7/api/sptest_9.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
            // echo $url = "http://123.0.17.7/api/test_3.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
            // $url = "https://api.ucbcapital.com/ucbapi/test_3.php?from_date=".$from_date . '&to_date='.$from_date.'&client_code='.$client_code;
            // $papana = file_get_contents($url);
            // $data['get_data'] = json_decode($papana);
            $data['from_date'] = date("Y-m-d", strtotime($request->from_date));
            // $data['to_date'] = date("Y-m-d", strtotime($request->to_date));
            /*echo "<pre>";
            print_r($data['get_data']);
            echo "</pre>";
            die;*/
        }
        return view('kiosk/kiosk_client_confirmation_note', $data);
    }


    public function print_kiosk_client_confirmation_note($from_date, $to_date) {

        $data = [];
        $client_code = session()->get( 'kAuth')[0]->client_code;
        $from_date = date("d M Y", strtotime($from_date));
        // $to_date = date("d M Y", strtotime($to_date));
        // $url = "http://123.0.17.7/api/sptest_9.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
        // $papana = file_get_contents($url);
        // $data['get_data'] = json_decode($papana);
        $data['from_date'] = $from_date;
        // $data['to_date'] = $to_date;
        $data['client_code'] = $client_code;
        // dd($data['get_data']);
        $api_token = DB::select('select api_token from tbl_token ORDER BY id DESC LIMIT 1');
        $data['api_token'] = 'Bearer '.$api_token[0]->api_token;
        return view('kiosk/print_kiosk_client_confirmation_note', $data);

    }


    public function passwordReset() {
          return view('kiosk.password_reset');
     }

    public function kiosk_logout()
    {
       session()->forget('kAuth');
       session_destroy();
       // print_r (Session::get('kAuth'));exit();
       return redirect('/kiosk_login');
    }
}
