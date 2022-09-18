<?php

namespace App\Http\Controllers;

use DB;
use App\Models\MKISTAT;
use App\Models\MKISTAT_ARCHIVE;
use App\Models\IDX;
use App\Models\MAN;
use App\Models\Event;
use App\Models\Product;
use App\Models\CircuitBreaker;
use App\Models\Settings;
use App\Models\IPO;
use App\Mail\EmailVerification;
use App\Mail\myTestMail;
use App\Mail\PasswordReset;
use App\Models\MarketCommentry;
use App\Models\EquityResearch;
use App\Models\EconomicUpdate;
use App\Models\UserBOAccountData;
use App\Models\Subscribers;
use App\Models\Notice;
use Auth;
use Mail;
use Response;
use Validator;
use DateTime;
use App\Models\Contact;
use App\Models\User;
use App\Models\PasswordPolicy;
use App\Models\Webcontent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home() {
        if(Auth::user()) {
            $user = Auth::user();
            if(Auth::user()->password_create_status == 1){
                if(Auth::user()->role == '0') 
                    return redirect('/home');
                else
                    return redirect('/user_panel');
            }
        }
        return view('auth.login');
    }

    // public function home() {
    //     if(Auth::user()) {
    //         if(Auth::user()->role == '0') 
    //             return redirect('/home');
    //         else
    //             return redirect('/user_panel');
    //     }
    //     return view('auth.login');
    // }

    public function new_page() {
        $data = array();
        $data['get_record'] = Contact::all();
        $data['get_record_2'] = MKISTAT::all();
        return view('web.new_page', $data);
    }

    public function search_result(Request $request) {

        $this->validate($request, [
                'search_text'   => 'required|min:3|max:200'
        ]);

        $data = [];
        $industry_data = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=all_data");
        $industry_data = json_decode($industry_data);
        $data['industry_data'] = $industry_data->data;
        $data['get_record'] = MKISTAT::where('MKISTAT_INSTRUMENT_CODE', 'like', '%'. $request->search_text . '%')->orderBy("MKISTAT_INSTRUMENT_CODE")->get();

        return view('web/search_result', $data);
    }

    public function user_registration(Request $request) {
        if($request->ajax()) {

            $chk = DB::select("SELECT * FROM user_bo_account_data WHERE dp_internal_reference_number='{$request->client_code}' AND email_id='{$request->email}'");
            if(!count($chk)) {
                echo "B.O account not found. You can not register here";
                return;
            }

            // chk existing user
            $chk_2 = User::where("email", $request->email)->get();
            if(count($chk_2)) {
                echo "This email already exists. You can not register again";
                return;
            }
            
            $user = new User;
            $user->name = $chk[0]->name_of_first_holder;
            $user->client_code = $request->client_code;
            $user->mobile = $request->mobile_no;
            $user->email = $request->email;
            $user->user_type = "Free";
            // $user->verified = 1;
            $user->email_token = sha1(time());
            $user->role = 1;
            $user->joined_date = date("Y-m-d", strtotime($request->joined_date));
            // $user->password = bcrypt($request->password);
            $user->password = bcrypt('120034');
            // echo "hi";exit();
            $user->save();

            // var_dump($user);
            \Mail::to($user->email)->send(new EmailVerification($user));
            // update user bo account table
            DB::select("UPDATE user_bo_account_data SET user_id='{$user->id}' WHERE dp_internal_reference_number='{$request->client_code}'");
            echo "We sent you an activation email. Check your email and click on the link to verify.";

            return ;
        }
    }

    public function passwordCreate($id)
    {
        return view('auth.passwordCreate',['id'=>$id]);
    }

    public function createUserPassword(Request $request, $id)
    {
        if($request->isMethod('post')){
            // echo "string";
            $user_info = User::where('id',$id)->first();
            if($user_info->verified){
                if($user_info->password_create_status == 0){
                    $user_info->password = bcrypt($request->password);
                    $user_info->password_create_status = 1;
                    $user_info->save();
                    return redirect('/')->with('success','Password Created successfully.You can now Login.');
                }else{
                    return redirect('/')->with('success','Your password already created.You can now Login.');
                }
            }else{
                return redirect('/')->with('failed','Please complete your email verify.');
            }
        }
    }

    public function open_bo_account() {
        $data = [];
        $data['dse_data'] = MKISTAT::all();
        $data['cse_data'] = DB::select("SELECT * FROM CSE_MKISTAT");
        $market_status = null;
        $cur_day = date("D");
        $start_time = DateTime::createFromFormat('H:i a', "10:30 pm");
        $end_time = DateTime::createFromFormat('H:i a', "2:29 pm");
        $current_time = DateTime::createFromFormat('H:i a', date("h:i a"));
        switch ($cur_day) {
            case 'Fri':
                $market_status = "Close";
                break;
            case 'Sat':
                $market_status = "Close";
                break;
            
            default:
                $market_status = ($current_time >= $start_time || $current_time <= $end_time) ? "Open" : "Close";
                break;
        }
        $data['market_status'] = $market_status;

        return view('web.open_bo_account', $data);
    }

    public function latest_data() {
        $data = [];
        $industry_data = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=all_data");
        $industry_data = json_decode($industry_data);
        $data['industry_data'] = $industry_data->data;

        $data['get_record'] = MKISTAT::orderBy("MKISTAT_INSTRUMENT_CODE")->get();
        $data['get_cse_data'] = DB::select("SELECT * FROM CSE_MKISTAT ORDER BY COMPANY_CODE");
        // dd($data['get_cse_data']);
        $data['last_update'] = $data['get_record'][0]->MKISTAT_LM_DATE_TIME;

        return view('web/latest_data', $data);
    }

    public function top_gainers() {
        $data = [];
        
        $industry_data = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=all_data");
        $industry_data = json_decode($industry_data);
        $data['industry_data'] = $industry_data->data;

        // $data['get_record'] = MKISTAT::orderBy("MKISTAT_HIGH_PRICE", "desc")->limit(30)->get();
        // $data['get_record'] = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_CLOSE_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_LM_DATE_TIME, ( (MKISTAT_PUB_LAST_TRADED_PRICE-MKISTAT_OPEN_PRICE)*(100/MKISTAT_OPEN_PRICE) ) AS CHANGES FROM MKISTAT ORDER BY CHANGES DESC LIMIT 30");

        $data['get_record'] = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_CLOSE_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_LM_DATE_TIME, ( (MKISTAT_CLOSE_PRICE-MKISTAT_YDAY_CLOSE_PRICE)*100/MKISTAT_YDAY_CLOSE_PRICE ) AS CHANGES FROM MKISTAT_ARCHIVE WHERE MKISTAT_QUOTE_BASES!='Z-EQ' ORDER BY CHANGES DESC LIMIT 30");
        // dd($data['get_record']);

        $data['cse_get_record'] = DB::select("SELECT COMPANY_CODE, CLOSE_PRICE, DAY_HIGH, DAY_LOW, PREV_CLOSE_PRICE, TRADE_DATE, (LAST_TRADED_PRICE-PREV_CLOSE_PRICE) AS CHANGES FROM CSE_MKISTAT ORDER BY CHANGES DESC LIMIT 30");
        $data['last_update'] = $data['get_record'][0]->MKISTAT_LM_DATE_TIME;
        
        return view('web/top_gainers', $data);
    }

    public function top_losers() {
        $data = [];
        
        $industry_data = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=all_data");
        $industry_data = json_decode($industry_data);
        $data['industry_data'] = $industry_data->data;

        // $data['get_record'] = MKISTAT::orderBy("MKISTAT_LOW_PRICE")->limit(30)->get();
        // $data['get_record'] = DB::select("SELECT MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_INSTRUMENT_CODE, MKISTAT_CLOSE_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_LM_DATE_TIME, (MKISTAT_PUB_LAST_TRADED_PRICE-MKISTAT_YDAY_CLOSE_PRICE) AS CHANGES FROM MKISTAT WHERE MKISTAT_QUOTE_BASES<>'A-TB' ORDER BY CHANGES ASC LIMIT 30");

        $data['get_record'] = DB::select("SELECT MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_INSTRUMENT_CODE, MKISTAT_CLOSE_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_LM_DATE_TIME, ( (MKISTAT_CLOSE_PRICE-MKISTAT_YDAY_CLOSE_PRICE)*(100/MKISTAT_OPEN_PRICE) ) AS CHANGES FROM MKISTAT_ARCHIVE WHERE MKISTAT_QUOTE_BASES<>'A-TB' AND MKISTAT_CLOSE_PRICE<>0 AND MKISTAT_HIGH_PRICE<>0 ORDER BY CHANGES ASC LIMIT 30");

        // dd($data['get_record']);

        $data['cse_get_record'] = DB::select("SELECT LAST_TRADED_PRICE, COMPANY_CODE, CLOSE_PRICE, DAY_HIGH, DAY_LOW, PREV_CLOSE_PRICE, TRADE_DATE, (LAST_TRADED_PRICE-PREV_CLOSE_PRICE) AS CHANGES FROM CSE_MKISTAT WHERE INSTRUMENT_GROUP<>'A-TB' AND LAST_TRADED_PRICE<>0 ORDER BY CHANGES ASC LIMIT 30");
        $data['last_update'] = $data['get_record'][0]->MKISTAT_LM_DATE_TIME;
        return view('web/top_losers', $data);
    }

    public function dse_papana_data() {

        $my_ratio = 10;
        $industry_name = "Insurance";
        $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
        $api_names = json_decode($api_names);
        $api_names = $api_names->data;

        // dd($api_names);

        // dd($api_names);

        // $get_record = DB:: select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT");

        $get_record = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='A'");

        $papuya = [];
        for($i=0; $i<count($get_record); $i++) {
            if($get_record[$i]->RATIO >= $my_ratio) {
                $papuya[] = $get_record[$i];
                unset($get_record[$i]);
            }
        }

        dd($get_record);
    }

    public function dse_latest_data(Request $request) {

        $keyword = $request->keyword;
        $industry_name = $request->industry_name;
        $category_name = $request->category_name;
        $ycp_ratio = $request->ycp_ratio;
        $column_sort = $request->column_sort;
        $alphabetic_val = $request->alphabetic_val;

        $alphabetic_con = "";
        $col_order = "";
        if($alphabetic_val !== "-1") {
            $alphabetic_con = " AND MKISTAT_INSTRUMENT_CODE LIKE '{$alphabetic_val}%' ";
        }
        if($column_sort !== "-1") {
            $col_order = " ORDER BY ".$column_sort . " DESC";
        }

        if($keyword) {
            // $get_record = MKISTAT::where('MKISTAT_INSTRUMENT_CODE', $keyword)->orderBy("MKISTAT_INSTRUMENT_CODE")->get();
            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE LIKE '{$keyword}%'".$col_order);
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $industry_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).")".$alphabetic_con);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $category_name !== '-1') {

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE LEFT(MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1') {

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT ".str_replace("AND", "WHERE", $alphabetic_con));

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='$category_name'".$alphabetic_con . $col_order);
            $totalRecord = count($get_record);
        }
        else if($industry_name === '-1' && $category_name !== '-1') {
            $get_record = DB::select("SELECT * FROM MKISTAT WHERE LEFT(MKISTAT_QUOTE_BASES, 1)='$category_name'".$alphabetic_con . $col_order);
            $totalRecord = count($get_record);
        }
        /*else if($industry_name && $keyword) {

            $names = IndustryData::where('INDUSTRY_NAME', $industry_name)->get(['COMPANY_CODE']);
            $all_name = [];
            foreach($names as $name) {
                $all_name[] = $name->COMPANY_CODE;
            }
            $get_record = MKISTAT::whereIn('MKISTAT_INSTRUMENT_CODE', $all_name)->orWhere('MKISTAT_INSTRUMENT_CODE', $keyword)->get();
            $totalRecord = count($get_record);

        }*/
        else if($industry_name !== '-1') {
            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).")".$alphabetic_con . $col_order);
            $totalRecord = count($get_record);

        }
        else {
            // $get_record = MKISTAT::orderBy("MKISTAT_INSTRUMENT_CODE")->get();
            $get_record = DB::select("SELECT * FROM MKISTAT".str_replace("AND", "WHERE", $alphabetic_con . $col_order));
            $totalRecord = count($get_record);
        }

        // $new_query = $query . " WHERE 1=1 ".$where." ORDER BY MKISTAT_INSTRUMENT_CODE DESC ";

        $x = 1;

        if (count($get_record) > 0) {
            foreach ($get_record as $obj) {

                $change = $obj->MKISTAT_PUB_LAST_TRADED_PRICE - $obj->MKISTAT_YDAY_CLOSE_PRICE;
                $change = number_format($change, 2);
                if($obj->MKISTAT_PUB_LAST_TRADED_PRICE > 1) {
                    if($change == 0) {
                      $change = "<span style='color:blue'>$change</span>";
                    }
                    else {
                      $sign = substr($change, 0, 1);
                      if($sign == "-") {
                        $change = "<span style='color:red'>$change</span>";
                      }
                      else {
                        $change = "<span style='color:green'>$change</span>";
                      }
                    }
                }
                else {
                $change = "---";
                }

                $arr =  array();
                $arr[] = $x;
                $arr[] = 'ok';
                $arr[] = $obj->MKISTAT_PUB_LAST_TRADED_PRICE;
                $arr[] = $obj->MKISTAT_HIGH_PRICE;
                $arr[] = $obj->MKISTAT_LOW_PRICE;
                $arr[] = $obj->MKISTAT_CLOSE_PRICE;
                $arr[] = $obj->MKISTAT_YDAY_CLOSE_PRICE;
                $arr[] = $change;
                $arr[] = $obj->MKISTAT_TOTAL_TRADES;
                $arr[] = number_format($obj->MKISTAT_TOTAL_VALUE, 2);
                $arr[] = $obj->MKISTAT_TOTAL_VOLUME;
                $x = $x + 1;
                $json[] = $arr;
            }
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $json   // total data array
                );
            echo  json_encode($json_data);
        }
        else {
            
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => []   // total data array
                );
            echo  json_encode($json_data);

        }



        // echo  json_encode($get_record);

    }

    public function cse_latest_data(Request $request) {

        $keyword = $request->keyword;
        $industry_name = $request->industry_name;
        $category_name = $request->category_name;
        $ycp_ratio = $request->ycp_ratio;
        $column_sort = $request->column_sort;
        $alphabetic_val = $request->alphabetic_val;

        $alphabetic_con = "";
        $col_order = "";
        if($alphabetic_val !== "-1") {
            $alphabetic_con = " AND COMPANY_CODE LIKE '{$alphabetic_val}%' ";
        }
        if($column_sort !== "-1") {

            if($column_sort == 'MKISTAT_PUB_LAST_TRADED_PRICE') {
                $column_sort = 'LAST_TRADED_PRICE';
            }
            else if($column_sort == 'MKISTAT_HIGH_PRICE') {
                $column_sort = 'DAY_HIGH';
            }
            else if($column_sort == 'MKISTAT_LOW_PRICE') {
                $column_sort = 'DAY_LOW';
            }
            else if($column_sort == 'MKISTAT_CLOSE_PRICE') {
                $column_sort = 'CLOSE_PRICE';
            }
            else if($column_sort == 'MKISTAT_YDAY_CLOSE_PRICE') {
                $column_sort = 'PREV_CLOSE_PRICE';
            }
            else if($column_sort == 'MKISTAT_TOTAL_TRADES') {
                $column_sort = 'NO_OF_TRADES';
            }
            else if($column_sort == 'MKISTAT_TOTAL_VALUE') {
                $column_sort = 'OPEN_PRICE';
            }
            else if($column_sort == 'MKISTAT_TOTAL_VOLUME') {
                $column_sort = 'VOLUME';
            }

            $col_order = " ORDER BY ".$column_sort . " DESC";
        }

        if($keyword) {
            $get_record = DB::select("SELECT * FROM CSE_MKISTAT WHERE COMPANY_CODE LIKE '{$keyword}%'".$col_order);
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT COMPANY_CODE, CSE_MKISTAT_INSTRUMENT_NUMBER, CSE_MKISTAT_QUOTE_BASES, CSE_MKISTAT_OPEN_PRICE, CSE_MKISTAT_PUB_LAST_TRADED_PRICE, CSE_MKISTAT_SPOT_LAST_TRADED_PRICE, CSE_MKISTAT_HIGH_PRICE, CSE_MKISTAT_LOW_PRICE, CSE_MKISTAT_CLOSE_PRICE, CSE_MKISTAT_YDAY_CLOSE_PRICE, CSE_MKISTAT_TOTAL_TRADES, CSE_MKISTAT_TOTAL_VOLUME, CSE_MKISTAT_TOTAL_VALUE, CSE_MKISTAT_PUBLIC_TOTAL_TRADES, CSE_MKISTAT_PUBLIC_TOTAL_VOLUME, CSE_MKISTAT_PUBLIC_TOTAL_VALUE, CSE_MKISTAT_SPOT_TOTAL_TRADES, CSE_MKISTAT_SPOT_TOTAL_VOLUME, (CSE_MKISTAT_YDAY_CLOSE_PRICE/CSE_MKISTAT_CLOSE_PRICE/CSE_MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM CSE_MKISTAT WHERE COMPANY_CODE IN (".implode(', ',$api_names).") AND LEFT(CSE_MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $industry_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT COMPANY_CODE, CSE_MKISTAT_INSTRUMENT_NUMBER, CSE_MKISTAT_QUOTE_BASES, CSE_MKISTAT_OPEN_PRICE, CSE_MKISTAT_PUB_LAST_TRADED_PRICE, CSE_MKISTAT_SPOT_LAST_TRADED_PRICE, CSE_MKISTAT_HIGH_PRICE, CSE_MKISTAT_LOW_PRICE, CSE_MKISTAT_CLOSE_PRICE, CSE_MKISTAT_YDAY_CLOSE_PRICE, CSE_MKISTAT_TOTAL_TRADES, CSE_MKISTAT_TOTAL_VOLUME, CSE_MKISTAT_TOTAL_VALUE, CSE_MKISTAT_PUBLIC_TOTAL_TRADES, CSE_MKISTAT_PUBLIC_TOTAL_VOLUME, CSE_MKISTAT_PUBLIC_TOTAL_VALUE, CSE_MKISTAT_SPOT_TOTAL_TRADES, CSE_MKISTAT_SPOT_TOTAL_VOLUME, (CSE_MKISTAT_YDAY_CLOSE_PRICE/CSE_MKISTAT_CLOSE_PRICE/CSE_MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM CSE_MKISTAT WHERE COMPANY_CODE IN (".implode(', ',$api_names).")".$alphabetic_con);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $category_name !== '-1') {

            $ratio_data = DB::select("SELECT COMPANY_CODE, CSE_MKISTAT_INSTRUMENT_NUMBER, CSE_MKISTAT_QUOTE_BASES, CSE_MKISTAT_OPEN_PRICE, CSE_MKISTAT_PUB_LAST_TRADED_PRICE, CSE_MKISTAT_SPOT_LAST_TRADED_PRICE, CSE_MKISTAT_HIGH_PRICE, CSE_MKISTAT_LOW_PRICE, CSE_MKISTAT_CLOSE_PRICE, CSE_MKISTAT_YDAY_CLOSE_PRICE, CSE_MKISTAT_TOTAL_TRADES, CSE_MKISTAT_TOTAL_VOLUME, CSE_MKISTAT_TOTAL_VALUE, CSE_MKISTAT_PUBLIC_TOTAL_TRADES, CSE_MKISTAT_PUBLIC_TOTAL_VOLUME, CSE_MKISTAT_PUBLIC_TOTAL_VALUE, CSE_MKISTAT_SPOT_TOTAL_TRADES, CSE_MKISTAT_SPOT_TOTAL_VOLUME, (CSE_MKISTAT_YDAY_CLOSE_PRICE/CSE_MKISTAT_CLOSE_PRICE/CSE_MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM CSE_MKISTAT WHERE LEFT(CSE_MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1') {

            $ratio_data = DB::select("SELECT COMPANY_CODE, CSE_MKISTAT_INSTRUMENT_NUMBER, CSE_MKISTAT_QUOTE_BASES, CSE_MKISTAT_OPEN_PRICE, CSE_MKISTAT_PUB_LAST_TRADED_PRICE, CSE_MKISTAT_SPOT_LAST_TRADED_PRICE, CSE_MKISTAT_HIGH_PRICE, CSE_MKISTAT_LOW_PRICE, CSE_MKISTAT_CLOSE_PRICE, CSE_MKISTAT_YDAY_CLOSE_PRICE, CSE_MKISTAT_TOTAL_TRADES, CSE_MKISTAT_TOTAL_VOLUME, CSE_MKISTAT_TOTAL_VALUE, CSE_MKISTAT_PUBLIC_TOTAL_TRADES, CSE_MKISTAT_PUBLIC_TOTAL_VOLUME, CSE_MKISTAT_PUBLIC_TOTAL_VALUE, CSE_MKISTAT_SPOT_TOTAL_TRADES, CSE_MKISTAT_SPOT_TOTAL_VOLUME, (CSE_MKISTAT_YDAY_CLOSE_PRICE/CSE_MKISTAT_CLOSE_PRICE/CSE_MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM CSE_MKISTAT ".str_replace("AND", "WHERE", $alphabetic_con));

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM CSE_MKISTAT WHERE COMPANY_CODE IN (".implode(', ',$api_names).") AND INSTRUMENT_GROUP='$category_name'".$alphabetic_con . $col_order);
            $totalRecord = count($get_record);
        }
        else if($industry_name === '-1' && $category_name !== '-1') {
            $get_record = DB::select("SELECT * FROM CSE_MKISTAT WHERE INSTRUMENT_GROUP='$category_name'".$alphabetic_con . $col_order);
            $totalRecord = count($get_record);
        }
        /*else if($industry_name && $keyword) {

            $names = IndustryData::where('INDUSTRY_NAME', $industry_name)->get(['COMPANY_CODE']);
            $all_name = [];
            foreach($names as $name) {
                $all_name[] = $name->COMPANY_CODE;
            }
            $get_record = CSE_MKISTAT::whereIn('COMPANY_CODE', $all_name)->orWhere('COMPANY_CODE', $keyword)->get();
            $totalRecord = count($get_record);

        }*/
        else if($industry_name !== '-1') {
            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM CSE_MKISTAT WHERE COMPANY_CODE IN (".implode(', ',$api_names).")".$alphabetic_con . $col_order);
            $totalRecord = count($get_record);

        }
        else {
            // $get_record = CSE_MKISTAT::orderBy("COMPANY_CODE")->get();
            $get_record = DB::select("SELECT * FROM CSE_MKISTAT".str_replace("AND", "WHERE", $alphabetic_con . $col_order));
            $totalRecord = count($get_record);
        }

        // $new_query = $query . " WHERE 1=1 ".$where." ORDER BY COMPANY_CODE DESC ";

        $x = 1;

        if (count($get_record) > 0) {
            foreach ($get_record as $obj) {

                $change = $obj->LAST_TRADED_PRICE - $obj->PREV_CLOSE_PRICE;
                $change = number_format($change, 2);
                if($obj->LAST_TRADED_PRICE > 1) {
                    if($change == 0) {
                      $change = "<span style='color:blue'>$change</span>";
                    }
                    else {
                      $sign = substr($change, 0, 1);
                      if($sign == "-") {
                        $change = "<span style='color:red'>$change</span>";
                      }
                      else {
                        $change = "<span style='color:green'>$change</span>";
                      }
                    }
                }
                else {
                $change = "---";
                }

                $arr =  array();
                $arr[] = $x;
                $arr[] = $obj->COMPANY_CODE;
                $arr[] = $obj->LAST_TRADED_PRICE;
                $arr[] = $obj->DAY_HIGH;
                $arr[] = $obj->DAY_LOW;
                $arr[] = $obj->CLOSE_PRICE;
                $arr[] = $obj->PREV_CLOSE_PRICE;
                $arr[] = $change;
                $arr[] = $obj->NO_OF_TRADES;
                $arr[] = number_format($obj->OPEN_PRICE, 2);
                $arr[] = $obj->VOLUME;
                $x = $x + 1;
                $json[] = $arr;
            }
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $json   // total data array
                );
            echo  json_encode($json_data);
        }
        else {
            
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => []   // total data array
                );
            echo  json_encode($json_data);

        }



        // echo  json_encode($get_record);

    }

    public function dse_top_losers_data(Request $request) {

        $column_sort = $request->column_sort;
        $industry_name = $request->industry_name;
        $category_name = $request->category_name;
        $ycp_ratio = $request->ycp_ratio;
        $alphabetic_val = $request->alphabetic_val;

        $alphabetic_con = "";
        $add_sql = "";
        if($alphabetic_val !== "-1") {
            $alphabetic_con = " AND MKISTAT_INSTRUMENT_CODE LIKE '{$alphabetic_val}%' ";
        }
        if($column_sort !== "-1") {
            $add_sql = " ORDER BY ".$column_sort . " DESC LIMIT 30";
        }
        else {
            $add_sql = " ORDER BY MKISTAT_LOW_PRICE ASC LIMIT 30";
        }


        if($ycp_ratio !== '-1' && $industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con.$add_sql);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $industry_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).")".$alphabetic_con.$add_sql);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $category_name !== '-1') {

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE LEFT(MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con.$add_sql);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1') {

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT ".str_replace("AND", "WHERE", $alphabetic_con.$add_sql));

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='$category_name'".$alphabetic_con.$add_sql);
            $totalRecord = count($get_record);
        }
        else if($industry_name === '-1' && $category_name !== '-1') {
            $get_record = DB::select("SELECT * FROM MKISTAT WHERE LEFT(MKISTAT_QUOTE_BASES, 1)='$category_name'".$alphabetic_con.$add_sql);
            $totalRecord = count($get_record);
        }
        else if($industry_name !== '-1') {
            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).")".$alphabetic_con.$add_sql);
            $totalRecord = count($get_record);

        }
        else {
            // $get_record = MKISTAT::orderBy("MKISTAT_INSTRUMENT_CODE")->get();
            $get_record = DB::select("SELECT * FROM MKISTAT".str_replace("AND", "WHERE", $alphabetic_con.$add_sql));
            $totalRecord = count($get_record);
        }

        // $new_query = $query . " WHERE 1=1 ".$where." ORDER BY MKISTAT_INSTRUMENT_CODE DESC ";

        $x = 1;

        if (count($get_record) > 0) {
            foreach ($get_record as $obj) {

                if($obj->MKISTAT_OPEN_PRICE == 0) {
                    $change = 0;
                }
                else {
                    $change = ($obj->MKISTAT_PUB_LAST_TRADED_PRICE - $obj->MKISTAT_OPEN_PRICE)*(100/$obj->MKISTAT_OPEN_PRICE);    
                }
                
                $change = number_format($change, 2);
                if($obj->MKISTAT_PUB_LAST_TRADED_PRICE > 1) {
                    if($change == 0) {
                      $change = "<span style='color:blue'>$change</span>";
                    }
                    else {
                      $sign = substr($change, 0, 1);
                      if($sign == "-") {
                        $change = "<span style='color:red'>$change</span>";
                      }
                      else {
                        $change = "<span style='color:green'>$change</span>";
                      }
                    }
                }
                else {
                $change = "---";
                }

                $arr =  array();
                $arr[] = $x;
                $arr[] = $obj->MKISTAT_INSTRUMENT_CODE;
                $arr[] = $obj->MKISTAT_CLOSE_PRICE;
                $arr[] = $obj->MKISTAT_HIGH_PRICE;
                $arr[] = $obj->MKISTAT_LOW_PRICE;
                $arr[] = $obj->MKISTAT_YDAY_CLOSE_PRICE;
                $arr[] = $change;
                $x = $x + 1;
                $json[] = $arr;
            }
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $json   // total data array
                );
            echo  json_encode($json_data);
        }
        else {
            
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => []   // total data array
                );
            echo  json_encode($json_data);

        }



        // echo  json_encode($get_record);

    }

    public function dse_top_gainers_data(Request $request) {

        $column_sort = $request->column_sort;
        $industry_name = $request->industry_name;
        $category_name = $request->category_name;
        $ycp_ratio = $request->ycp_ratio;
        $alphabetic_val = $request->alphabetic_val;
        $add_sql = "";

        $alphabetic_con = "";
        if($alphabetic_val !== "-1") {
            $alphabetic_con = " AND MKISTAT_INSTRUMENT_CODE LIKE '{$alphabetic_val}%' ";
        }
        if($column_sort !== "-1") {
            $add_sql = " ORDER BY ".$column_sort . " DESC LIMIT 30";
        }
        else {
            $add_sql = " ORDER BY MKISTAT_HIGH_PRICE DESC LIMIT 30";
        }


        if($ycp_ratio !== '-1' && $industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con.$add_sql);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $industry_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).")".$alphabetic_con.$add_sql);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1' && $category_name !== '-1') {

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT WHERE LEFT(MKISTAT_QUOTE_BASES, 1)='{$category_name}'".$alphabetic_con.$add_sql);

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($ycp_ratio !== '-1') {

            $ratio_data = DB::select("SELECT MKISTAT_INSTRUMENT_CODE, MKISTAT_INSTRUMENT_NUMBER, MKISTAT_QUOTE_BASES, MKISTAT_OPEN_PRICE, MKISTAT_PUB_LAST_TRADED_PRICE, MKISTAT_SPOT_LAST_TRADED_PRICE, MKISTAT_HIGH_PRICE, MKISTAT_LOW_PRICE, MKISTAT_CLOSE_PRICE, MKISTAT_YDAY_CLOSE_PRICE, MKISTAT_TOTAL_TRADES, MKISTAT_TOTAL_VOLUME, MKISTAT_TOTAL_VALUE, MKISTAT_PUBLIC_TOTAL_TRADES, MKISTAT_PUBLIC_TOTAL_VOLUME, MKISTAT_PUBLIC_TOTAL_VALUE, MKISTAT_SPOT_TOTAL_TRADES, MKISTAT_SPOT_TOTAL_VOLUME, (MKISTAT_YDAY_CLOSE_PRICE/MKISTAT_CLOSE_PRICE/MKISTAT_YDAY_CLOSE_PRICE)*100 AS RATIO FROM MKISTAT ".str_replace("AND", "WHERE", $alphabetic_con.$add_sql));

            $get_record = [];
            for($i=0; $i<count($ratio_data); $i++) {
                if($ratio_data[$i]->RATIO >= $ycp_ratio) {
                    $get_record[] = $ratio_data[$i];
                    unset($ratio_data[$i]);
                }
            }
            $totalRecord = count($get_record);
        }
        else if($industry_name !== '-1' && $category_name !== '-1') {

            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).") AND LEFT(MKISTAT_QUOTE_BASES, 1)='$category_name'".$alphabetic_con.$add_sql);
            $totalRecord = count($get_record);
        }
        else if($industry_name === '-1' && $category_name !== '-1') {
            $get_record = DB::select("SELECT * FROM MKISTAT WHERE LEFT(MKISTAT_QUOTE_BASES, 1)='$category_name'".$alphabetic_con.$add_sql);
            $totalRecord = count($get_record);
        }
        else if($industry_name !== '-1') {
            $api_names = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=filter_data&name=".$industry_name);
            $api_names = json_decode($api_names);
            $api_names = $api_names->data;

            $get_record = DB::select("SELECT * FROM MKISTAT WHERE MKISTAT_INSTRUMENT_CODE IN (".implode(', ',$api_names).")".$alphabetic_con.$add_sql);
            $totalRecord = count($get_record);

        }
        else {
            $get_record = DB::select("SELECT * FROM MKISTAT".str_replace("AND", "WHERE", $alphabetic_con).$add_sql);
            $totalRecord = count($get_record);
        }

        // $new_query = $query . " WHERE 1=1 ".$where." ORDER BY MKISTAT_INSTRUMENT_CODE DESC ";

        $x = 1;

        if (count($get_record) > 0) {
            foreach ($get_record as $obj) {

                $change = ($obj->MKISTAT_PUB_LAST_TRADED_PRICE - $obj->MKISTAT_OPEN_PRICE)*(100/$obj->MKISTAT_OPEN_PRICE);
                $change = number_format($change, 2);
                if($obj->MKISTAT_PUB_LAST_TRADED_PRICE > 1) {
                    if($change == 0) {
                      $change = "<span style='color:blue'>$change</span>";
                    }
                    else {
                      $sign = substr($change, 0, 1);
                      if($sign == "-") {
                        $change = "<span style='color:red'>$change</span>";
                      }
                      else {
                        $change = "<span style='color:green'>$change</span>";
                      }
                    }
                }
                else {
                    $change = "---";
                }

                $arr =  array();
                $arr[] = $x;
                $arr[] = $obj->MKISTAT_INSTRUMENT_CODE;
                $arr[] = $obj->MKISTAT_CLOSE_PRICE;
                $arr[] = $obj->MKISTAT_HIGH_PRICE;
                $arr[] = $obj->MKISTAT_LOW_PRICE;
                $arr[] = $obj->MKISTAT_YDAY_CLOSE_PRICE;
                $arr[] = $change;
                $x = $x + 1;
                $json[] = $arr;
            }
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $json   // total data array
                );
            echo  json_encode($json_data);
        }
        else {
            
            $json_data = array(
                "draw"            => intval( $_REQUEST['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval( $totalRecord ),  // total number of records
                "recordsFiltered" => intval( $totalRecord ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => []   // total data array
                );
            echo  json_encode($json_data);

        }



        // echo  json_encode($get_record);

    }

    public function market_data() {

        $data = [];
        $ds30 = DB::select("SELECT IDX_CAPITAL_VALUE capital_value,  IDX_DATE_TIME idx_time FROM IDX WHERE IDX_INDEX_ID='DS30' ORDER BY IDX_DATE_TIME DESC");
        $data['ds30'] = $ds30;

        $data['ds30_min'] = DB::select("SELECT MIN(IDX_CAPITAL_VALUE) AS MIN_VAL FROM IDX WHERE IDX_INDEX_ID='DS30'");
        $data['dses_min'] = DB::select("SELECT MIN(IDX_CAPITAL_VALUE) AS DSES_MIN_VAL FROM IDX WHERE IDX_INDEX_ID='DSES'");
        $data['dsex_min'] = DB::select("SELECT MIN(IDX_CAPITAL_VALUE) AS DSEX_MIN_VAL FROM IDX WHERE IDX_INDEX_ID='DSEX'");

        return view('web/market_data', $data);
    }

    public function get_dse_board_index_web() {
        $ds30 = DB::select("SELECT IDX_CAPITAL_VALUE capital_value,  IDX_DATE_TIME idx_time FROM IDX WHERE IDX_INDEX_ID='DS30' ORDER BY IDX_DATE_TIME");
        echo json_encode($ds30);
    }

    public function get_dse_board_index_dses_web() {
        $dses = DB::select("SELECT IDX_CAPITAL_VALUE capital_value_dses,  IDX_DATE_TIME idx_time_dses FROM IDX WHERE IDX_INDEX_ID='DSES' ORDER BY IDX_DATE_TIME");
        echo json_encode($dses);
    }

    public function get_dse_board_index_dsex_web() {
        $dsex = DB::select("SELECT IDX_CAPITAL_VALUE capital_value_dsex,  IDX_DATE_TIME idx_time_dsex FROM IDX WHERE IDX_INDEX_ID='DSEX' ORDER BY IDX_DATE_TIME");
        echo json_encode($dsex);
    }

    public function about_uftcl() {
        $data = [];
        $data['about'] = DB::table('company_profile')->first();
        return view('web/about_uftcl', $data);
    }

    public function management_profile() {
        $data = [];
        $data['first'] = DB::select("SELECT * FROM management_profile ORDER BY id LIMIT 2");
        $data['rest'] = DB::select("SELECT * FROM management_profile ORDER BY id LIMIT 2, 20");
        return view('web/management_profile', $data);
    }

    public function about_director() {
        $data = [];
        $data['first'] = DB::select("SELECT * FROM board_of_director ORDER BY id LIMIT 1");
        $data['rest'] = DB::select("SELECT * FROM board_of_director ORDER BY id LIMIT 1, 20");
        return view('web/about_director', $data);
    }

    public function contact_us() {
        $data = [];
        $data['get_record'] = Contact::all();
        // dd($data['get']);
        return view('web/contact_us', $data);
    }

    public function news() {
        $data = [];
        $data['get_record'] = MAN::limit(30)->orderBy('MAN_ANNOUNCEMENT_DATE_TIME', 'desc')->get();
        // dd($data);
        return view('web/news', $data);
    }

    public function events() {
        $data = [];
        $data['agm'] = Event::where('category', 'AGM')->get();
        $data['egm'] = Event::where('category', 'EGM')->get();
        $data['rdd'] = Event::where('category', 'Record Data Divident')->get();
        return view('web/events', $data);
    }

    public function product_and_service() {
        $data = [];
        $data['get_record'] = Product::all();
        // dd($data);
        return view('web.product_and_service', $data);
    }

    public function circuit_breaker() {

        $data = [];
        $industry_data = file_get_contents("https://api.akijcapital.com/data.php?password=entertech&data_type=all_data");
        $industry_data = json_decode($industry_data);
        $data['industry_data'] = $industry_data->data;

        $data['get_record'] = MKISTAT::orderBy("MKISTAT_INSTRUMENT_CODE")->get();
        $data['get_cse_data'] = DB::select("SELECT * FROM CSE_MKISTAT ORDER BY COMPANY_CODE");
        // dd($data['get_cse_data']);
        $data['dse_breaker'] = CircuitBreaker::where('breaker_type', 'dse')->get();
        $data['cse_breaker'] = CircuitBreaker::where('breaker_type', 'cse')->get();

        $data['last_update'] = $data['get_record'][0]->MKISTAT_LM_DATE_TIME;
        return view('web/circuit_breaker', $data);

    }

    public function research() {
        $data = [];
        $data['get_record'] = EquityResearch::all();
        return view('web/research', $data);
    }

    public function market_update() {
        $data = [];
        $data['get_record'] = Webcontent::where('file_type', 'market_update')->first();
        return view('web/market_update', $data);
    }

    public function downloads() {
        $data = [];
        $data['get_record'] = Webcontent::where('file_type', 'downloads')->get();
        return view('web/downloads', $data);
    }

    public function market_commentry() {
        $data = [];
        $data['get_record'] = MarketCommentry::all();
        return view('web/market_commentry', $data);
    }

    public function economic_update() {
        $data = [];
        $data['get_record'] = EconomicUpdate::all();
        return view('web/economic_update', $data);
    }


    public function web_open_bo_account(Request $request) {
        $data = [];
        $action = Input::get('submit');
        
        if($action == "Save Account") {
            // dd($_POST);

            $first_holder_picture = $request->file('first_holder_picture');
            if($first_holder_picture) {
                $filename = $_FILES['first_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $first_holder_picture = $request->file('first_holder_picture');
                $filePath = $first_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['first_holder_picture']['tmp_name'], $destination);
            }
            $first_holder_picture = ($first_holder_picture) ? $_FILES['first_holder_picture']['name'] : "";

            $signature = $request->file('signature');
            if($signature) {
                $filename = $_FILES['signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $signature = $request->file('signature');
                $filePath = $signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['signature']['tmp_name'], $destination);
            }
            $signature = ($signature) ? $_FILES['signature']['name'] : "";

            $nid_or_passport_copy = $request->file('nid_or_passport_copy');
            if($nid_or_passport_copy) {
                $filename = $_FILES['nid_or_passport_copy']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nid_or_passport_copy = $request->file('nid_or_passport_copy');
                $filePath = $nid_or_passport_copy->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nid_or_passport_copy']['tmp_name'], $destination);
            }
            $nid_or_passport_copy = ($nid_or_passport_copy) ? $_FILES['nid_or_passport_copy']['name'] : "";
            // -- end 1st holder

            $second_holder_picture = $request->file('second_holder_picture');
            if($second_holder_picture) {
                $filename = $_FILES['second_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $second_holder_picture = $request->file('second_holder_picture');
                $filePath = $second_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['second_holder_picture']['tmp_name'], $destination);
            }
            $second_holder_picture = ($second_holder_picture) ? $_FILES['second_holder_picture']['name'] : "";

            $second_holder_signature = $request->file('second_holder_signature');
            if($second_holder_signature) {
                $filename = $_FILES['second_holder_signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $second_holder_signature = $request->file('second_holder_signature');
                $filePath = $second_holder_signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['second_holder_signature']['tmp_name'], $destination);
            }
            $second_holder_signature = ($second_holder_signature) ? $_FILES['second_holder_signature']['name'] : "";
            // -- end second holder

            $third_holder_picture = $request->file('third_holder_picture');
            if($third_holder_picture) {
                $filename = $_FILES['third_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $third_holder_picture = $request->file('third_holder_picture');
                $filePath = $third_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['third_holder_picture']['tmp_name'], $destination);
            }
            $third_holder_picture = ($third_holder_picture) ? $_FILES['third_holder_picture']['name'] : "";

            $third_holder_signature = $request->file('third_holder_signature');
            if($third_holder_signature) {
                $filename = $_FILES['third_holder_signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $third_holder_signature = $request->file('third_holder_signature');
                $filePath = $third_holder_signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['third_holder_signature']['tmp_name'], $destination);
            }
            $third_holder_signature = ($third_holder_signature) ? $_FILES['third_holder_signature']['name'] : "";
            // -- end third holder

            $first_holder_national_id_file = $request->file('first_holder_national_id_file');
            if($first_holder_national_id_file) {
                $filename = $_FILES['first_holder_national_id_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $first_holder_national_id_file = $request->file('first_holder_national_id_file');
                $filePath = $first_holder_national_id_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['first_holder_national_id_file']['tmp_name'], $destination);
            }
            $first_holder_national_id_file = ($first_holder_national_id_file) ? $_FILES['first_holder_national_id_file']['name'] : "";

            $first_holder_passport_file = $request->file('first_holder_passport_file');
            if($first_holder_passport_file) {
                $filename = $_FILES['first_holder_passport_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $first_holder_passport_file = $request->file('first_holder_passport_file');
                $filePath = $first_holder_passport_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['first_holder_passport_file']['tmp_name'], $destination);
            }
            $first_holder_passport_file = ($first_holder_passport_file) ? $_FILES['first_holder_passport_file']['name'] : "";

            $bank_statement = $request->file('bank_statement');
            if($bank_statement) {
                $filename = $_FILES['bank_statement']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $bank_statement = $request->file('bank_statement');
                $filePath = $bank_statement->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['bank_statement']['tmp_name'], $destination);
            }
            $bank_statement = ($bank_statement) ? $_FILES['bank_statement']['name'] : "";

            $tin = $request->file('tin');
            if($tin) {
                $filename = $_FILES['tin']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $tin = $request->file('tin');
                $filePath = $tin->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['tin']['tmp_name'], $destination);
            }
            $tin = ($tin) ? $_FILES['tin']['name'] : "";
            // -- end personal details

            $nominee_picture = $request->file('nominee_picture');
            if($nominee_picture) {
                $filename = $_FILES['nominee_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_picture = $request->file('nominee_picture');
                $filePath = $nominee_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_picture']['tmp_name'], $destination);
            }
            $nominee_picture = ($nominee_picture) ? $_FILES['nominee_picture']['name'] : "";

            $nominee_signature = $request->file('nominee_signature');
            if($nominee_signature) {
                $filename = $_FILES['nominee_signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_signature = $request->file('nominee_signature');
                $filePath = $nominee_signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_signature']['tmp_name'], $destination);
            }
            $nominee_signature = ($nominee_signature) ? $_FILES['nominee_signature']['name'] : "";

            $nominee_nid_file = $request->file('nominee_nid_file');
            if($nominee_nid_file) {
                $filename = $_FILES['nominee_nid_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_nid_file = $request->file('nominee_nid_file');
                $filePath = $nominee_nid_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_nid_file']['tmp_name'], $destination);
            }
            $nominee_nid_file = ($nominee_nid_file) ? $_FILES['nominee_nid_file']['name'] : "";

            $nominee_passport_file = $request->file('nominee_passport_file');
            if($nominee_passport_file) {
                $filename = $_FILES['nominee_passport_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_passport_file = $request->file('nominee_passport_file');
                $filePath = $nominee_passport_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_passport_file']['tmp_name'], $destination);
            }
            $nominee_passport_file = ($nominee_passport_file) ? $_FILES['nominee_passport_file']['name'] : "";
            // -- end nominee


            /*$joint_holder_picture = $request->file('joint_holder_picture');
            if($joint_holder_picture) {
                $filename = $_FILES['joint_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $joint_holder_picture = $request->file('joint_holder_picture');
                $filePath = $joint_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['joint_holder_picture']['tmp_name'], $destination);
            }
            $joint_holder_picture = ($joint_holder_picture) ? $_FILES['joint_holder_picture']['name'] : "";

            $nominee_nid = $request->file('nominee_nid');
            if($nominee_nid) {
                $filename = $_FILES['nominee_nid']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_nid = $request->file('nominee_nid');
                $filePath = $nominee_nid->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_nid']['tmp_name'], $destination);
            }
            $nominee_nid = ($nominee_nid) ? $_FILES['nominee_nid']['name'] : "";

            $board_regulation = $request->file('board_regulation');
            if($board_regulation) {
                $filename = $_FILES['board_regulation']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $board_regulation = $request->file('board_regulation');
                $filePath = $board_regulation->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['board_regulation']['tmp_name'], $destination);
            }
            $board_regulation = ($board_regulation) ? $_FILES['board_regulation']['name'] : "";

            $trade_licence = $request->file('trade_licence');
            if($trade_licence) {
                $filename = $_FILES['trade_licence']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $trade_licence = $request->file('trade_licence');
                $filePath = $trade_licence->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['trade_licence']['tmp_name'], $destination);
            }
            $trade_licence = ($trade_licence) ? $_FILES['trade_licence']['name'] : "";

            $authorize_person_nid = $request->file('authorize_person_nid');
            if($authorize_person_nid) {
                $filename = $_FILES['authorize_person_nid']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $authorize_person_nid = $request->file('authorize_person_nid');
                $filePath = $authorize_person_nid->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['authorize_person_nid']['tmp_name'], $destination);
            }
            $authorize_person_nid = ($authorize_person_nid) ? $_FILES['authorize_person_nid']['name'] : "";

            $authorize_person_photo = $request->file('authorize_person_photo');
            if($authorize_person_photo) {
                $filename = $_FILES['authorize_person_photo']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $authorize_person_photo = $request->file('authorize_person_photo');
                $filePath = $authorize_person_photo->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['authorize_person_photo']['tmp_name'], $destination);
            }
            $authorize_person_photo = ($authorize_person_photo) ? $_FILES['authorize_person_photo']['name'] : "kaku";*/



            $bo_account = new UserBOAccountData;
            $bo_account->bo_identification_number = $request->bo_identification_number;

            $bo_account->bo_category = $request->bo_category;
            $bo_account->bo_type = $request->bo_type;
            $bo_account->name_of_first_holder = $request->name_of_first_holder;
            $bo_account->bo_middle_name = $request->bo_middle_name;
            $bo_account->bo_last_name = $request->bo_last_name;
            $bo_account->bo_short_name = $request->bo_short_name;
            $bo_account->bo_title = $request->bo_title;
            $bo_account->bo_suffix = $request->bo_suffix;
            $bo_account->first_holder_picture = $first_holder_picture;
            $bo_account->signature = $signature;
            $bo_account->nid_or_passport_copy = $nid_or_passport_copy;

            $bo_account->second_joint_holder = $request->second_joint_holder;
            $bo_account->second_holder_middle_name = $request->second_holder_middle_name;
            $bo_account->second_holder_last_name = $request->second_holder_last_name;
            $bo_account->second_holder_short_name = $request->second_holder_short_name;
            $bo_account->second_holder_title = $request->second_holder_title;
            $bo_account->second_holder_sufix = $request->second_holder_sufix;
            $bo_account->second_holder_national_id = $request->second_holder_national_id;
            $bo_account->second_holder_picture = $second_holder_picture;
            $bo_account->second_holder_signature = $second_holder_signature;
            
            $bo_account->third_joint_holder = $request->third_joint_holder;
            $bo_account->third_holder_middle_name = $request->third_holder_middle_name;
            $bo_account->third_holder_last_name = $request->third_holder_last_name;
            $bo_account->third_holder_short_name = $request->third_holder_short_name;
            $bo_account->third_holder_title = $request->third_holder_title;
            $bo_account->third_holder_sufix = $request->third_holder_sufix;
            $bo_account->third_holder_picture = $third_holder_picture;
            $bo_account->third_holder_signature = $third_holder_signature;

            $bo_account->address_1 = $request->address_1;
            $bo_account->address_2 = $request->address_2;
            $bo_account->address_3 = $request->address_3;
            $bo_account->city = $request->city;
            $bo_account->state = $request->state;
            $bo_account->country = $request->country;
            $bo_account->postal_code = $request->postal_code;
            $bo_account->phone_number = $request->phone_number;
            $bo_account->first_holder_national_id = $request->first_holder_national_id;
            $bo_account->first_holder_national_id_file = $first_holder_national_id_file;
            $bo_account->sex_code = $request->sex_code;
            $bo_account->occupation = $request->occupation;
            $bo_account->email_id = $request->email_id;
            $bo_account->father_or_husband_name = $request->father_or_husband_name;
            $bo_account->mother_name = $request->mother_name;
            $bo_account->fax_number = $request->fax_number;
            $bo_account->passport_number = $request->passport_number;
            $bo_account->passport_issue_date = date("Y-m-d", strtotime($request->passport_issue_date));
            $bo_account->passport_expiry_date = date("Y-m-d", strtotime($request->passport_expiry_date));
            $bo_account->passport_issue_place = $request->passport_issue_place;
            $bo_account->first_holder_passport_file = $first_holder_passport_file;
            $bo_account->bank_name = $request->bank_name;
            $bo_account->bank_branch_name = $request->bank_branch_name;
            $bo_account->bank_account_number = $request->bank_account_number;
            $bo_account->electronic_dividend_flag = $request->electronic_dividend_flag;
            $bo_account->tax_exemption_flag = $request->tax_exemption_flag;
            $bo_account->tax_identification_number = $request->tax_identification_number;
            $bo_account->bank_routine_number = $request->bank_routine_number;
            $bo_account->bank_identification_code = $request->bank_identification_code;
            $bo_account->international_bank_account_number = $request->international_bank_account_number;
            $bo_account->bank_swift_code = $request->bank_swift_code;
            $bo_account->bank_statement = $bank_statement;
            $bo_account->tin = $tin;
            $bo_account->residency_flag = $request->residency_flag;
            $bo_account->nationality = $request->nationality;
            $bo_account->exchange_id = $request->exchange_id;
            $bo_account->date_of_birth = date("Y-m-d", strtotime($request->date_of_birth));
            $bo_account->trading_id = $request->trading_id;
            $bo_account->registration_number = $request->registration_number;
            $bo_account->dp_internal_reference_number = $request->dp_internal_reference_number;
            $bo_account->statement_cycle_code = $request->statement_cycle_code;
            
            // -- nominee
            $bo_account->nominee_first_name = $request->nominee_first_name;
            $bo_account->nominee_middle_name = $request->nominee_middle_name;
            $bo_account->nominee_last_name = $request->nominee_last_name;
            $bo_account->nominee_short_name = $request->nominee_short_name;
            $bo_account->nominee_title = $request->nominee_title;
            $bo_account->nominee_suffix = $request->nominee_suffix;
            $bo_account->nominee_picture = $nominee_picture;
            $bo_account->nominee_signature = $nominee_signature;
            $bo_account->nominee_address_1 = $request->nominee_address_1;
            $bo_account->nominee_address_2 = $request->nominee_address_2;
            $bo_account->nominee_address_3 = $request->nominee_address_3;
            $bo_account->nominee_city = $request->nominee_city;
            $bo_account->nominee_state = $request->nominee_state;
            $bo_account->nominee_country = $request->nominee_country;
            $bo_account->nominee_postal_code = $request->nominee_postal_code;
            $bo_account->nominee_phone_number = $request->nominee_phone_number;
            $bo_account->nominee_nid = $request->nominee_nid;
            $bo_account->nominee_nid_file = $nominee_nid_file;
            $bo_account->nominee_passport_number = $request->nominee_passport_number;
            $bo_account->nominee_passport_issue_date = $request->nominee_passport_issue_date;
            $bo_account->nominee_passport_expiry_date = $request->nominee_passport_expiry_date;
            $bo_account->nominee_passport_issue_place = $request->nominee_passport_issue_place;
            $bo_account->nominee_passport_file = $nominee_passport_file;
            $bo_account->nominee_residency_flag = $request->nominee_residency_flag;
            $bo_account->nominee_nationality = $request->nominee_nationality;
            $bo_account->nominee_date_of_birth = date("Y-m-d", strtotime($request->nominee_date_of_birth));
            $bo_account->nominee_exchange_id = $request->nominee_exchange_id;
            $bo_account->nominee_trading_id = $request->nominee_trading_id;
            $bo_account->nominee_registration_number = $request->nominee_registration_number;
            $bo_account->nominee_dp_internal_reference_number = $request->nominee_dp_internal_reference_number;
            $bo_account->nominee_statement_cycle_code = $request->nominee_statement_cycle_code;

            /*$bo_account->first_holder_picture = $first_holder_picture;
            $bo_account->joint_holder_picture = $joint_holder_picture;
            $bo_account->nominee_picture = $nominee_picture;
            $bo_account->nid_or_passport_copy = $nid_or_passport_copy;
            $bo_account->signature = $signature;
            $bo_account->nominee_nid = $nominee_nid;
            $bo_account->nominee_signature = $nominee_signature;
            $bo_account->board_regulation = $board_regulation;
            $bo_account->trade_licence = $trade_licence;
            $bo_account->authorize_person_nid = $authorize_person_nid;
            $bo_account->authorize_person_photo = $authorize_person_photo;*/
            $bo_account->save();

            return redirect('web_open_bo_account')->with('flash_msg', 'B.O Account Created Successfully');
        }

        return view('web/web_open_bo_account', $data);
    }

/*********************New Web Open Bo Account*************************/
    public function iframe_open_bo_account(Request $request) {
        $data = [];
        $action = Input::get('submit');
        //echo($action);die;
        if($action == "Save Account") {
            // dd($_POST);
            // Log::info('Get Save Account data 1');
            $first_holder_picture = $request->file('first_holder_picture');
            if($first_holder_picture) {
                $filename = $_FILES['first_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $first_holder_picture = $request->file('first_holder_picture');
                $filePath = $first_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['first_holder_picture']['tmp_name'], $destination);
                // Log::info('Get first_holder_picture 2');
            }
            $first_holder_picture = ($first_holder_picture) ? $_FILES['first_holder_picture']['name'] : "";

            $signature = $request->file('signature');
            if($signature) {
                $filename = $_FILES['signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $signature = $request->file('signature');
                $filePath = $signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['signature']['tmp_name'], $destination);
            }
            $signature = ($signature) ? $_FILES['signature']['name'] : "";
            // Log::info('Get signature 3');
            $nid_or_passport_copy = $request->file('nid_or_passport_copy');
            if($nid_or_passport_copy) {
                $filename = $_FILES['nid_or_passport_copy']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nid_or_passport_copy = $request->file('nid_or_passport_copy');
                $filePath = $nid_or_passport_copy->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nid_or_passport_copy']['tmp_name'], $destination);
            }
            // Log::info('Get nid_or_passport_copy 4');
            $nid_or_passport_copy = ($nid_or_passport_copy) ? $_FILES['nid_or_passport_copy']['name'] : "";
            // -- end 1st holder

            $second_holder_picture = $request->file('second_holder_picture');
            if($second_holder_picture) {
                $filename = $_FILES['second_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $second_holder_picture = $request->file('second_holder_picture');
                $filePath = $second_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['second_holder_picture']['tmp_name'], $destination);
            }
            // Log::info('Get nid_or_passport_copy 5');
            $second_holder_picture = ($second_holder_picture) ? $_FILES['second_holder_picture']['name'] : "";

            $second_holder_signature = $request->file('second_holder_signature');
            if($second_holder_signature) {
                $filename = $_FILES['second_holder_signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $second_holder_signature = $request->file('second_holder_signature');
                $filePath = $second_holder_signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['second_holder_signature']['tmp_name'], $destination);
            }
            // Log::info('Get second_holder_signature 6');
            $second_holder_signature = ($second_holder_signature) ? $_FILES['second_holder_signature']['name'] : "";
            // -- end second holder

            $third_holder_picture = $request->file('third_holder_picture');
            if($third_holder_picture) {
                $filename = $_FILES['third_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $third_holder_picture = $request->file('third_holder_picture');
                $filePath = $third_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['third_holder_picture']['tmp_name'], $destination);
                // Log::info('Get third_holder_picture 7');
            }
            $third_holder_picture = ($third_holder_picture) ? $_FILES['third_holder_picture']['name'] : "";

            $third_holder_signature = $request->file('third_holder_signature');

            if($third_holder_signature) {
                $filename = $_FILES['third_holder_signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $third_holder_signature = $request->file('third_holder_signature');
                $filePath = $third_holder_signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['third_holder_signature']['tmp_name'], $destination);
                // Log::info('Get third_holder_signature 8');
            }
            $third_holder_signature = ($third_holder_signature) ? $_FILES['third_holder_signature']['name'] : "";
            // -- end third holder

            $first_holder_national_id_file = $request->file('first_holder_national_id_file');
            if($first_holder_national_id_file) {
                $filename = $_FILES['first_holder_national_id_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $first_holder_national_id_file = $request->file('first_holder_national_id_file');
                $filePath = $first_holder_national_id_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['first_holder_national_id_file']['tmp_name'], $destination);
                // Log::info('Get first_holder_national_id_file 9');
            }
            $first_holder_national_id_file = ($first_holder_national_id_file) ? $_FILES['first_holder_national_id_file']['name'] : "";

            $first_holder_passport_file = $request->file('first_holder_passport_file');
            if($first_holder_passport_file) {
                $filename = $_FILES['first_holder_passport_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $first_holder_passport_file = $request->file('first_holder_passport_file');
                $filePath = $first_holder_passport_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['first_holder_passport_file']['tmp_name'], $destination);
                // Log::info('Get first_holder_passport_file 10');
            }
            $first_holder_passport_file = ($first_holder_passport_file) ? $_FILES['first_holder_passport_file']['name'] : "";

            $bank_statement = $request->file('bank_statement');
            if($bank_statement) {
                $filename = $_FILES['bank_statement']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $bank_statement = $request->file('bank_statement');
                $filePath = $bank_statement->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['bank_statement']['tmp_name'], $destination);
                // Log::info('Get first_holder_passport_file 11');
            }
            $bank_statement = ($bank_statement) ? $_FILES['bank_statement']['name'] : "";

            $tin = $request->file('tin');
            if($tin) {
                $filename = $_FILES['tin']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $tin = $request->file('tin');
                $filePath = $tin->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['tin']['tmp_name'], $destination);
            }
            $tin = ($tin) ? $_FILES['tin']['name'] : "";
            // -- end personal details

            $nominee_picture = $request->file('nominee_picture');
            if($nominee_picture) {
                $filename = $_FILES['nominee_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_picture = $request->file('nominee_picture');
                $filePath = $nominee_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_picture']['tmp_name'], $destination);
                // Log::info('Get nominee_picture 12');
            }
            $nominee_picture = ($nominee_picture) ? $_FILES['nominee_picture']['name'] : "";

            $nominee_signature = $request->file('nominee_signature');
            if($nominee_signature) {
                $filename = $_FILES['nominee_signature']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_signature = $request->file('nominee_signature');
                $filePath = $nominee_signature->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_signature']['tmp_name'], $destination);
                Log::info('Get nominee_signature 13');
            }
            $nominee_signature = ($nominee_signature) ? $_FILES['nominee_signature']['name'] : "";

            $nominee_nid_file = $request->file('nominee_nid_file');
            if($nominee_nid_file) {
                $filename = $_FILES['nominee_nid_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_nid_file = $request->file('nominee_nid_file');
                $filePath = $nominee_nid_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_nid_file']['tmp_name'], $destination);
                Log::info('Get nominee_nid_file 14');
            }
            $nominee_nid_file = ($nominee_nid_file) ? $_FILES['nominee_nid_file']['name'] : "";

            $nominee_passport_file = $request->file('nominee_passport_file');
            if($nominee_passport_file) {
                $filename = $_FILES['nominee_passport_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('iframe_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_passport_file = $request->file('nominee_passport_file');
                $filePath = $nominee_passport_file->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_passport_file']['tmp_name'], $destination);
                // Log::info('Get nominee_passport_file 15');
            }
            $nominee_passport_file = ($nominee_passport_file) ? $_FILES['nominee_passport_file']['name'] : "";
            // -- end nominee


            /*$joint_holder_picture = $request->file('joint_holder_picture');
            if($joint_holder_picture) {
                $filename = $_FILES['joint_holder_picture']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $joint_holder_picture = $request->file('joint_holder_picture');
                $filePath = $joint_holder_picture->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['joint_holder_picture']['tmp_name'], $destination);
            }
            $joint_holder_picture = ($joint_holder_picture) ? $_FILES['joint_holder_picture']['name'] : "";

            $nominee_nid = $request->file('nominee_nid');
            if($nominee_nid) {
                $filename = $_FILES['nominee_nid']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $nominee_nid = $request->file('nominee_nid');
                $filePath = $nominee_nid->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['nominee_nid']['tmp_name'], $destination);
            }
            $nominee_nid = ($nominee_nid) ? $_FILES['nominee_nid']['name'] : "";

            $board_regulation = $request->file('board_regulation');
            if($board_regulation) {
                $filename = $_FILES['board_regulation']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $board_regulation = $request->file('board_regulation');
                $filePath = $board_regulation->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['board_regulation']['tmp_name'], $destination);
            }
            $board_regulation = ($board_regulation) ? $_FILES['board_regulation']['name'] : "";

            $trade_licence = $request->file('trade_licence');
            if($trade_licence) {
                $filename = $_FILES['trade_licence']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $trade_licence = $request->file('trade_licence');
                $filePath = $trade_licence->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['trade_licence']['tmp_name'], $destination);
            }
            $trade_licence = ($trade_licence) ? $_FILES['trade_licence']['name'] : "";

            $authorize_person_nid = $request->file('authorize_person_nid');
            if($authorize_person_nid) {
                $filename = $_FILES['authorize_person_nid']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $authorize_person_nid = $request->file('authorize_person_nid');
                $filePath = $authorize_person_nid->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['authorize_person_nid']['tmp_name'], $destination);
            }
            $authorize_person_nid = ($authorize_person_nid) ? $_FILES['authorize_person_nid']['name'] : "";

            $authorize_person_photo = $request->file('authorize_person_photo');
            if($authorize_person_photo) {
                $filename = $_FILES['authorize_person_photo']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('web_open_bo_account')
                    ->with('flash_msg', 'Invalid file extension. file is not permitted');
                }
                // get the file
                $authorize_person_photo = $request->file('authorize_person_photo');
                $filePath = $authorize_person_photo->getRealPath();
                $destination = public_path() ."/custom_files/bo_files/". $filename;
                move_uploaded_file($_FILES['authorize_person_photo']['tmp_name'], $destination);
            }
            $authorize_person_photo = ($authorize_person_photo) ? $_FILES['authorize_person_photo']['name'] : "kaku";*/



            $bo_account = new UserBOAccountData;
            $bo_account->bo_identification_number = $request->bo_identification_number;

            $bo_account->bo_category = $request->bo_category;
            $bo_account->bo_type = $request->bo_type;
            $bo_account->name_of_first_holder = $request->name_of_first_holder;
            $bo_account->bo_middle_name = $request->bo_middle_name;
            $bo_account->bo_last_name = $request->bo_last_name;
            $bo_account->bo_short_name = $request->bo_short_name;
            $bo_account->bo_title = $request->bo_title;
            $bo_account->bo_suffix = $request->bo_suffix;
            $bo_account->first_holder_picture = $first_holder_picture;
            $bo_account->signature = $signature;
            $bo_account->nid_or_passport_copy = $nid_or_passport_copy;

            $bo_account->second_joint_holder = $request->second_joint_holder;
            $bo_account->second_holder_middle_name = $request->second_holder_middle_name;
            $bo_account->second_holder_last_name = $request->second_holder_last_name;
            $bo_account->second_holder_short_name = $request->second_holder_short_name;
            $bo_account->second_holder_title = $request->second_holder_title;
            $bo_account->second_holder_sufix = $request->second_holder_sufix;
            $bo_account->second_holder_national_id = $request->second_holder_national_id;
            $bo_account->second_holder_picture = $second_holder_picture;
            $bo_account->second_holder_signature = $second_holder_signature;
            
            $bo_account->third_joint_holder = $request->third_joint_holder;
            $bo_account->third_holder_middle_name = $request->third_holder_middle_name;
            $bo_account->third_holder_last_name = $request->third_holder_last_name;
            $bo_account->third_holder_short_name = $request->third_holder_short_name;
            $bo_account->third_holder_title = $request->third_holder_title;
            $bo_account->third_holder_sufix = $request->third_holder_sufix;
            $bo_account->third_holder_picture = $third_holder_picture;
            $bo_account->third_holder_signature = $third_holder_signature;

            $bo_account->address_1 = $request->address_1;
            $bo_account->address_2 = $request->address_2;
            $bo_account->address_3 = $request->address_3;
            $bo_account->city = $request->city;
            $bo_account->state = $request->state;
            $bo_account->country = $request->country;
            $bo_account->postal_code = $request->postal_code;
            $bo_account->phone_number = $request->phone_number;
            $bo_account->first_holder_national_id = $request->first_holder_national_id;
            $bo_account->first_holder_national_id_file = $first_holder_national_id_file;
            $bo_account->sex_code = $request->sex_code;
            $bo_account->occupation = $request->occupation;
            $bo_account->email_id = $request->email_id;
            $bo_account->father_or_husband_name = $request->father_or_husband_name;
            $bo_account->mother_name = $request->mother_name;
            $bo_account->fax_number = $request->fax_number;
            $bo_account->passport_number = $request->passport_number;
            $bo_account->passport_issue_date = date("Y-m-d", strtotime($request->passport_issue_date));
            $bo_account->passport_expiry_date = date("Y-m-d", strtotime($request->passport_expiry_date));
            $bo_account->passport_issue_place = $request->passport_issue_place;
            $bo_account->first_holder_passport_file = $first_holder_passport_file;
            $bo_account->bank_name = $request->bank_name;
            $bo_account->bank_branch_name = $request->bank_branch_name;
            $bo_account->bank_account_number = $request->bank_account_number;
            $bo_account->electronic_dividend_flag = $request->electronic_dividend_flag;
            $bo_account->tax_exemption_flag = $request->tax_exemption_flag;
            $bo_account->tax_identification_number = $request->tax_identification_number;
            $bo_account->bank_routine_number = $request->bank_routine_number;
            $bo_account->bank_identification_code = $request->bank_identification_code;
            $bo_account->international_bank_account_number = $request->international_bank_account_number;
            $bo_account->bank_swift_code = $request->bank_swift_code;
            $bo_account->bank_statement = $bank_statement;
            $bo_account->tin = $tin;
            $bo_account->residency_flag = $request->residency_flag;
            $bo_account->nationality = $request->nationality;
            $bo_account->exchange_id = $request->exchange_id;
            $bo_account->date_of_birth = date("Y-m-d", strtotime($request->date_of_birth));
            $bo_account->trading_id = $request->trading_id;
            $bo_account->registration_number = $request->registration_number;
            $bo_account->dp_internal_reference_number = $request->dp_internal_reference_number;
            $bo_account->statement_cycle_code = $request->statement_cycle_code;
            
            // -- nominee
            $bo_account->nominee_first_name = $request->nominee_first_name;
            $bo_account->nominee_middle_name = $request->nominee_middle_name;
            $bo_account->nominee_last_name = $request->nominee_last_name;
            $bo_account->nominee_short_name = $request->nominee_short_name;
            $bo_account->nominee_title = $request->nominee_title;
            $bo_account->nominee_suffix = $request->nominee_suffix;
            $bo_account->nominee_picture = $nominee_picture;
            $bo_account->nominee_signature = $nominee_signature;
            $bo_account->nominee_address_1 = $request->nominee_address_1;
            $bo_account->nominee_address_2 = $request->nominee_address_2;
            $bo_account->nominee_address_3 = $request->nominee_address_3;
            $bo_account->nominee_city = $request->nominee_city;
            $bo_account->nominee_state = $request->nominee_state;
            $bo_account->nominee_country = $request->nominee_country;
            $bo_account->nominee_postal_code = $request->nominee_postal_code;
            $bo_account->nominee_phone_number = $request->nominee_phone_number;
            $bo_account->nominee_nid = $request->nominee_nid;
            $bo_account->nominee_nid_file = $nominee_nid_file;
            $bo_account->nominee_passport_number = $request->nominee_passport_number;
            $bo_account->nominee_passport_issue_date = $request->nominee_passport_issue_date;
            $bo_account->nominee_passport_expiry_date = $request->nominee_passport_expiry_date;
            $bo_account->nominee_passport_issue_place = $request->nominee_passport_issue_place;
            $bo_account->nominee_passport_file = $nominee_passport_file;
            $bo_account->nominee_residency_flag = $request->nominee_residency_flag;
            $bo_account->nominee_nationality = $request->nominee_nationality;
            $bo_account->nominee_date_of_birth = date("Y-m-d", strtotime($request->nominee_date_of_birth));
            $bo_account->nominee_exchange_id = $request->nominee_exchange_id;
            $bo_account->nominee_trading_id = $request->nominee_trading_id;
            $bo_account->nominee_registration_number = $request->nominee_registration_number;
            $bo_account->nominee_dp_internal_reference_number = $request->nominee_dp_internal_reference_number;
            $bo_account->nominee_statement_cycle_code = $request->nominee_statement_cycle_code;

            /*$bo_account->first_holder_picture = $first_holder_picture;
            $bo_account->joint_holder_picture = $joint_holder_picture;
            $bo_account->nominee_picture = $nominee_picture;
            $bo_account->nid_or_passport_copy = $nid_or_passport_copy;
            $bo_account->signature = $signature;
            $bo_account->nominee_nid = $nominee_nid;
            $bo_account->nominee_signature = $nominee_signature;
            $bo_account->board_regulation = $board_regulation;
            $bo_account->trade_licence = $trade_licence;
            $bo_account->authorize_person_nid = $authorize_person_nid;
            $bo_account->authorize_person_photo = $authorize_person_photo;*/
            // Log::info('Get Data',json_decode(json_encode($bo_account)));
            $bo_account->save();

            return redirect('iframe_open_bo_account')->with('flash_msg', 'B.O Account Created Successfully');
        }

        return view('web/new_open_bo_account', $data);
    }
/*********************************************************************/
    public function subscribe_as_member(Request $request) {
        $email = $request->email;
        // chk mail
        $subs = Subscribers::where('email', $email)->get();
        if(count($subs)) {
            return redirect()->back()->withErrors(['This email already used.']);
        }

        $subscribers = new Subscribers();
        $subscribers->email = $email;
        $subscribers->save();
        return redirect()->back()->with('flash_msg', 'Thank You! You are subscribed successfully');
    }

    public function notice_board() {
        $data = [];
        $data['get_record'] = Notice::orderBy('id', 'desc')->get();
        return view('web/notice_board', $data);
    }

    public function ipo() {
        $data = [];
        $data['get_record'] = IPO::orderBy('id', 'desc')->get();
        return view('web/ipo', $data);
    }

    public function generatecryptourl() {
        echo 'here';
    }

    /****************User New Login Process***************************/

     public function user_new_login(Request $request) {
          return view('web/user_new_login');
     }
    /*****************************************************************/
    /************************* password reset ********************************/
    public function passwordReset() {
          return view('auth.passwords.reset');
     }

     public function sendPasswordResetEmail(Request $request)
     {
        $email = $request->email;
        $data = User::where('email',$email)->first();
        if(!empty($data)) {
            if($data->verified){
               if($data->password_create_status){
                    $data->email_token = sha1(time());
                    $data->save();
                    \Mail::to($email)->send(new PasswordReset($data));
                    return redirect('/')->with('success','We sent you an password resent link email. Check your email and click on the link to reset your password.');
               }else{
                return redirect('/')->with('failed', 'First your check and verify your mail and create your password.');
               }
            }else{
                return redirect('/')->with('failed', 'First your check and verify your mail and create your password.');
            }
        }
        return redirect('/password/reset')->with('failed','Please give your valid email id.');
     }


     public function changePassword($id)
     {
        return view('auth.change_password',['id'=>$id]);
     }

    public function saveNewPassword(Request $request, $id)
    {
        // dd('hi');
        // if($request->isMethod('post')){
            $pass_info = PasswordPolicy::orderBy('created_at','desc')->limit(1)->get();
            //dd($pass_info);
            if(strlen($request->password) < $pass_info[0]->password_length) {
                return redirect('change-password')->with('failed', 'Password must be '.$pass_info[0]->password_length.' or more than '.$pass_info[0]->password_length. ' character');
            }elseif($pass_info[0]->digit != null && !preg_match("#[0-9]+#",$request->password)) {
                return redirect()->back()->with('failed',"Your Password Must Contain At Least 1 Number!");
            }elseif($pass_info[0]->uppercase != null && !preg_match("#[A-Z]+#",$request->password)) {
                return redirect()->back()->with('failed',"Your Password Must Contain At Least 1 Capital Letter!");
            }elseif($pass_info[0]->lowercase != null && !preg_match("#[a-z]+#",$request->password)) {
                return redirect()->back()->with('failed',"Your Password Must Contain At Least 1 Lowercase Letter!");
            }elseif($pass_info[0]->special_character != null && !preg_match("#\W+#",$request->password)) {
                return redirect()->back()->with('failed',"Your Password Must Contain At Least 1 Special Character!");
            }

            if($request->password !== $request->confirm_password) {
                return redirect()->back()->with('failed', 'New password & Confirm Password didn\'t match');
            }

            $user_info = User::where('id',$id)->first();
            $user_info->password = bcrypt($request->password);
            $user_info->save();
            return redirect('/')->with('success','Password changed successfully.You can now Login.');
        
    }

    public function tested(Request $request, $id)
    {
        dd('hi');
    }
    /*************************************************************************/
    public function credcheck($email, $password){
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed...
            return 'success';
        }else{
            return 'failed';
        }
    }

    public function getbcryptpass($password){
        return bcrypt($password);
    }

    public function send_mail(){

        $myEmail = "arifkhanshubro@gmail.com";
        \Mail::to($myEmail)->send(new myTestMail());

        dd("Mail Successfully");
    }
}
