<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Excel;
use App\Models\IDX;
use App\Models\MKISTAT;
use App\Models\CompanyProfile;
use App\Models\BoardOfDirector;
use App\Models\ManagementProfile;
use App\Models\CircuitBreaker;
use App\Models\Category;
use App\Models\IndustryData;
use App\Models\Settings;
use App\Models\PasswordPolicy;
use App\Models\Industry;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use App\Models\MAN;
use App\Models\UserBOAccountData;
use App\Models\UserBOAccountDataDemo;
use App\Models\Mail\EmailVerification;
use App\Models\Webcontent;
use App\Models\MarketCommentry;
use App\Models\EquityResearch;
use App\Models\EconomicUpdate;
use App\Models\ClientLimits;
use App\Models\BatchData;
use App\Models\OrderManagement;
use App\Models\WithdrawRequest;
use App\Models\Subscribers;
use App\Models\Notice;
use App\Models\IPO;
use App\Models\IPOSetting;
use App\Models\IPOApplication;
use App\Models\Group;
use App\Models\Deposit;
use Auth;
use Response;
use Validator;
use DateTime;
use Hash;
use File;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    // -- admin dashboard
    public function user_analytics(Request $request) {
        if($request->ajax()) {

            if($request->page_type == "edit_user_get") {
                $user_data = User::where('id', $request->user_id)->first();
                return json_encode($user_data);
            }
            else if($request->page_type == "add_user") {

                $user = new User;
                $user->name = $request->name;
                $user->user_id = $request->user_id;
                $user->email = $request->email;
                $user->permissions = implode(",", $request->permissions);
                $user->user_type = $request->user_type;
                $user->mobile = $request->mobile;
                $user->verified = 1;
                $user->role = 2;
                $user->joined_date = date("Y-m-d", strtotime($request->joined_at));
                $user->password = bcrypt($request->password);
                $user->save();

                echo "User created successfully";
                return;

            }
            else if($request->page_type == "edit_user") {

                $user = User::find($request->post_id);
                $user->name = $request->name;
                $user->user_id = $request->user_id;
                $user->email = $request->email;
                $user->permissions = implode(",", $request->permissions);
                // $user->user_type = $request->user_type;
                $user->mobile = $request->mobile;
                $user->verified = 1;
                $user->role = 2;
                $user->joined_date = date("Y-m-d", strtotime($request->joined_at));
                // $user->password = bcrypt($request->password);
                $user->save();

                echo "User updated successfully";
                return;

            }
        }

        $data = [];
        $all_data = DB::select("SELECT id, (SELECT COUNT(*) FROM users WHERE role=1) AS TOT_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Free') AS FREE_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Premium') AS PREMIUM_USER, (SELECT COUNT(*) FROM users WHERE role=2) AS ADMIN_USER FROM users LIMIT 1");
        $data['all_data'] = $all_data[0];
        $data['permissions'] = DB::select("SELECT * FROM permission");
        $data['get_data'] = DB::select("SELECT * FROM users WHERE role=2 AND id!=40");
        return view('admin.user_analytics', $data);
    }

    public function delete_user_analytics($id) {
        $users=User::find($id);
        if ($users){
            $users->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
    }

    public function about_company_profile(Request $request) {
        $data = [];
        $action = Input::get('submit');
        $data['data'] = CompanyProfile::first();

        if($action == "Save change") {
            $this->validate($request, [
                'first_title'   => 'required',
                'first_description' => 'required',
                // 'second_title'   => 'required',
                // 'second_description' => 'required',
                // 'third_description'  => 'required',
                // 'fourth_description' => 'required',
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('about_company_profile')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/company_profile/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;

            $company_profile = CompanyProfile::find($id);
            $company_profile->first_title = $request->first_title;
            $company_profile->first_description = $request->first_description;
            $company_profile->upload_file = ($upload) ? $filename : $data['data']->upload_file;
            $company_profile->second_title = $request->second_title;
            $company_profile->second_description = $request->second_description;
            $company_profile->third_description = $request->third_description;
            $company_profile->fourth_description = $request->fourth_description;
            // $company_profile->updated_by = Auth::user()->name;
            $company_profile->save();


            return redirect('about_company_profile')->with('flash_msg', 'Company profile updated successfully');
        }

        return view('admin.about_company_profile', $data);
    }

    public function about_board_of_director(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required',
                'name'  => 'required',
                'designation'   => 'required',
                'description'   => 'required',
                'facebook_url'  => 'required',
                'twitter_url'   => 'required',
                'linkedin_url'  => 'required',
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('about_board_of_director')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/board_of_director/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;

            $board_of_director = new BoardOfDirector;
            $board_of_director->name = $request->name;
            $board_of_director->designation = $request->designation;
            $board_of_director->description = $request->description;
            $board_of_director->image = ($upload) ? $filename : $data['data']->upload_file;
            $board_of_director->facebook_url = $request->facebook_url;
            $board_of_director->twitter_url = $request->twitter_url;
            $board_of_director->linkedin_url = $request->linkedin_url;
            // $board_of_director->created_by = Auth::user()->id;
            $board_of_director->save();


            return redirect('about_board_of_director')->with('flash_msg', 'Data inserted successfully');
        }

        return view('admin.about_board_of_director', $data);
    }

    public function edit_director_profile_data(Request $request, $id) {
        $data = [];
        $data['val'] = BoardOfDirector::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'name'  => 'required',
                'designation'   => 'required',
                'description'   => 'required',
                'facebook_url'  => 'required',
                'twitter_url'   => 'required',
                'linkedin_url'  => 'required',
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('edit_director_profile_data')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/board_of_director/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            // $id = $request->id;

            $board_of_director = BoardOfDirector::find($id);
            $board_of_director->name = $request->name;
            $board_of_director->designation = $request->designation;
            $board_of_director->description = $request->description;
            if($upload) {
                $board_of_director->image = $filename;
            }
            $board_of_director->facebook_url = $request->facebook_url;
            $board_of_director->twitter_url = $request->twitter_url;
            $board_of_director->linkedin_url = $request->linkedin_url;
            // $board_of_director->created_by = Auth::user()->id;
            $board_of_director->save();


            return redirect('director_profile_data')->with('flash_msg', 'Data updated successfully');
        }

        return view('admin.edit_director_profile_data', $data);
    }

    public function edit_market_data_events(Request $request, $id) {
        $data = [];
        $events = Event::where('id', $id)->first();
        //$action = Input::get('submit');
        $total_news_data= MAN::count();
        $total_events_data= Event::count();
        $total_category_data= Category::count();
        $total_industry_data= Industry::count();
        $total_industryData= IndustryData::count();
        $action = $request->submit;
        if($action == "Save change") {
            $board_of_director = Event::find($id);
            $board_of_director->category = $request->category;
            $board_of_director->trading_code = $request->trading_code;
            $board_of_director->year_end = $request->year_end;
            $board_of_director->divident_in = $request->divident_in;
            $board_of_director->vanue = $request->vanue;
            $board_of_director->time = $request->time;
            $board_of_director->save();

            return redirect('market_data_events')->with('success', 'Data updated successfully');
        }

        return view('admin.edit_market_data_events', compact('events','total_news_data','total_events_data','total_industry_data','total_industryData'));
    }

    // public function product_and_service(Request $request) {
    //     $data = [];
    //     $data['get_record'] = Product::all();
    //     return view('admin/product_and_service', $data);
    // }

    // public function delete_product_and_service($id) {
    //     $id = $id;
    //     Product::where('id', $id)->delete();
    //     return redirect('product_and_service')->with('flash_msg', 'Product deleted successfully');
    // }

    // public function create_product_and_service(Request $request) {
    //     $data = [];
    //     $action = Input::get('submit');

    //     if($action == "Save change") {
    //         $this->validate($request, [
    //             'upload_file'   => 'required',
    //             'product_name'  => 'required',
    //             'description'   => 'required',
    //         ]);

    //         $upload = $request->file('upload_file');
    //         if($upload) {
    //             $filename = $_FILES['upload_file']['name'];
    //             $ext = pathinfo($filename, PATHINFO_EXTENSION);
    //             $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
    //             if(!in_array($ext, $accept_files)) {
    //                 return redirect()->route('create_product_and_service')
    //                 ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
    //             }
    //             // get the file
    //             $upload = $request->file('upload_file');
    //             $filePath = $upload->getRealPath();
    //             $destination = public_path() ."/custom_files/products/". $filename;
    //             move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
    //         }
    //         $id = $request->id;

    //         $products = new Product;
    //         $products->product_name = $request->product_name;
    //         $products->description = $request->description;
    //         $products->image = ($upload) ? $filename : $data['data']->upload_file;
    //         $products->save();


    //         return redirect('product_and_service')->with('flash_msg', 'Data inserted successfully');
    //     }

    //     return view('admin/create_product_and_service', $data);
    // }

    // public function edit_product_and_service(Request $request, $id) {
    //     $data = [];
    //     $data['val'] = Product::where('id', $id)->first();
    //     $action = Input::get('submit');

    //     if($action == "Save change") {
    //         $this->validate($request, [
    //             'product_name'  => 'required',
    //             'description'   => 'required',
    //         ]);
    //         $upload = $request->file('upload_file');
    //         if($upload) {
    //             $filename = $_FILES['upload_file']['name'];
    //             $ext = pathinfo($filename, PATHINFO_EXTENSION);
    //             $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
    //             if(!in_array($ext, $accept_files)) {
    //                 return redirect()->route('edit_product_and_service')
    //                 ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
    //             }
    //             // get the file
    //             $upload = $request->file('upload_file');
    //             $filePath = $upload->getRealPath();
    //             $destination = public_path() ."/custom_files/products/". $filename;
    //             move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
    //         }
    //         // $id = $request->id;

    //         // dd($data['val']->image);
    //         $product = Product::find($id);
    //         $product->product_name = $request->product_name;
    //         $product->description = $request->description;
    //         $product->image = ($upload) ? $filename : $data['val']->image;
    //         $product->save();


    //         return redirect('product_and_service')->with('flash_msg', 'Data updated successfully');
    //     }

    //     return view('admin/edit_product_and_service', $data);
    // }

    // -- start group account

    public function manage_group_account(Request $request) {
        $data = [];
        $get_record = Group::all();
        $total_group_data=Group::count();
        return view('admin.group.manage_group_account', compact('get_record','total_group_data'));
    }

    public function delete_group_account($id) {
        $groups=Group::find($id);
        if ($groups){
            $groups->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
        // $id = $id;
        // Group::where('id', $id)->delete();
        // return redirect('manage_group_account')->with('flash_msg', 'Group deleted successfully');
    }

    public function create_group_account(Request $request) {
        $data = [];
        $total_group_data=Group::count();
        $action = $request->submit;
        // $data['get_data'] = UserBOAccountData::limit(5)->get(['bo_identification_number', 'name_of_first_holder']);
        // $data['get_data'] = UserBOAccountData::get(['bo_identification_number', 'name_of_first_holder']);
        $get_data = DB::select("SELECT ubad.*, u.* FROM user_bo_account_data AS ubad INNER JOIN users AS u ON u.id=ubad.user_id");
        // dd($data['get_data']);

        // dd($_POST);

        if($action == "Save change") {
            $this->validate($request, [
                'group_name'   => 'required',
                'bo_ids'  => 'required'
            ]);

            $bo_ids = implode(",", $request->bo_ids);
            // dd($bo_ids);

            $group = new Group;
            $group->group_name = $request->group_name;
            $group->bo_ids = $bo_ids;
            $group->save();

            return redirect('manage_group_account')->with('success', 'Group created successfully');
            //return redirect('manage_group_account')->with('flash_msg', 'Group created successfully');
        }

        return view('admin.group.create_group_account', compact('get_data','total_group_data'));
    }

    public function edit_group_account(Request $request, $id) {
        $data = [];
        $data['val'] = Group::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'group_name'  => 'required',
                'bo_ids'   => 'required',
            ]);

            $group = Group::find($id);
            $group->group_name = $request->group_name;
            $group->bo_ids = $request->bo_ids;
            $group->save();


            return redirect('manage_group_account')->with('flash_msg', 'Group updated successfully');
        }

        return view('admin.group.edit_group_account', $data);
    }

    // -- end group account

    public function about_management_profile(Request $request) {
        $data = [];
        $data['get_record'] = ManagementProfile::all();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required',
                'name'  => 'required',
                'designation'   => 'required',
                'description'   => 'required',
                'facebook_url'  => 'required',
                'twitter_url'   => 'required',
                'linkedin_url'  => 'required',
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('about_management_profile')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/management_profile/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;

            $management_profile = new ManagementProfile;
            $management_profile->name = $request->name;
            $management_profile->designation = $request->designation;
            $management_profile->description = $request->description;
            $management_profile->image = ($upload) ? $filename : $data['data']->upload_file;
            $management_profile->facebook_url = $request->facebook_url;
            $management_profile->twitter_url = $request->twitter_url;
            $management_profile->linkedin_url = $request->linkedin_url;
            // $management_profile->created_by = Auth::user()->id;
            $management_profile->save();


            return redirect('about_management_profile')->with('flash_msg', 'Data inserted successfully');
        }

        return view('admin.about_management_profile', $data);
    }

    public function edit_management_profile(Request $request, $id) {
        $data = [];
        $data['val'] = ManagementProfile::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'name'  => 'required',
                'designation'   => 'required',
                'description'   => 'required',
                'facebook_url'  => 'required',
                'twitter_url'   => 'required',
                'linkedin_url'  => 'required',
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('edit_management_profile')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/management_profile/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            // $id = $request->id;

            $management_profile = ManagementProfile::find($id);
            $management_profile->name = $request->name;
            $management_profile->designation = $request->designation;
            $management_profile->description = $request->description;
            if($upload) {
                $management_profile->image = $filename;
            }
            $management_profile->facebook_url = $request->facebook_url;
            $management_profile->twitter_url = $request->twitter_url;
            $management_profile->linkedin_url = $request->linkedin_url;
            // $management_profile->created_by = Auth::user()->id;
            $management_profile->save();


            return redirect('management_profile_data')->with('flash_msg', 'Data updated successfully');
        }

        return view('admin.edit_management_profile', $data);
    }

    public function management_profile_data() {
        $data = [];
        $data['get_record'] = ManagementProfile::all();
        return view('admin.management_profile_data', $data);
    }

    public function web_content(Request $request) {
        $data = [];
        $data['data'] = Webcontent::where('file_type', 'market_update')->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["pdf", "PDF"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->back()
                    ->with('flash_msg', 'Invalid file extension. permitted file is .pdf');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/web_content/market_data/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;

            $market_update = Webcontent::find($id);
            $market_update->upload_file = ($upload) ? $filename : $data['data']->upload_file;
            $market_update->save();


            return redirect()->back()->with('flash_msg', 'Market data pdf updated successfully');
        }

        return view('admin.content.web_content', $data);
    }

    // start downloads panel
    public function manage_user_downloads() {
        $data = [];
        $data['get_record'] = Webcontent::where('file_type', 'downloads')->get();
        return view('admin.content.manage_user_downloads', $data);
    }

    public function add_user_downloads(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'title'  => 'required',
                'upload_file'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('add_user_downloads')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg, .pdf & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/web_content/downloads/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;
            $downloads = new Webcontent;
            $downloads->title = $request->title;
            $downloads->file_type = "downloads";
            $downloads->upload_file = ($upload) ? $filename : "";
            $downloads->save();


            return redirect('add_user_downloads')->with('flash_msg', 'User downloads created successfully');
        }

        return view('admin.content.add_user_downloads', $data);
    }

    public function edit_user_downloads(Request $request, $id) {

        $data = [];
        $data['val'] = Webcontent::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required',
                'title'  => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif", "pdf"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->back()
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg, .pdf & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/web_content/downloads/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            // $id = $request->id;

            $downloads = Webcontent::find($id);
            $downloads->title = $request->title;
            if($upload) {
                $downloads->upload_file = $filename;
            }
            $downloads->save();


            return redirect('manage_user_downloads')->with('flash_msg', 'File updated successfully');
        }

        return view('admin.content.edit_user_downloads', $data);
    }

    public function delete_user_downloads($id) {
        $id = $id;
        Webcontent::where('id', $id)->delete();
        return redirect('manage_user_downloads')->with('flash_msg', 'File deleted successfully');
    }

    // -- end notice panel

    // start notice panel
    public function manage_notice() {
        $data = [];
        $data['get_record'] = Notice::all();
        return view('admin.manage_notice', $data);
    }

    public function create_notice(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'notice_title'  => 'required',
                'notice_description'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('create_notice')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/notice/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;

            $notice = new Notice;
            $notice->notice_title = $request->notice_title;
            $notice->notice_description = $request->notice_description;
            $notice->upload_file = ($upload) ? $filename : "";
            $notice->save();


            return redirect('create_notice')->with('flash_msg', 'Notice created successfully');
        }

        return view('admin.create_notice', $data);
    }

    public function edit_notice(Request $request, $id) {

        $data = [];
        $data['val'] = Notice::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'notice_title'  => 'required',
                'notice_description'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('edit_notice')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/notice/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            // $id = $request->id;

            $notice = Notice::find($id);
            $notice->notice_title = $request->notice_title;
            $notice->notice_description = $request->notice_description;
            if($upload) {
                $notice->upload_file = $filename;
            }
            $notice->save();


            return redirect('manage_notice')->with('flash_msg', 'Notice updated successfully');
        }

        return view('admin.edit_notice', $data);
    }

    public function delete_notice($id) {
        $id = $id;
        Notice::where('id', $id)->delete();
        return redirect('manage_notice')->with('flash_msg', 'Notice deleted successfully');
    }

    // -- end notice panel

    // start ipo panel
    public function manage_ipo() {
        $data = [];
        $data['get_record'] = IPO::all();
        return view('admin.manage_ipo', $data);
    }

    public function create_ipo(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'ipo_title'  => 'required',
                'ipo_description'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('create_ipo')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/ipo/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $request->id;

            $ipo = new IPO;
            $ipo->ipo_title = $request->ipo_title;
            $ipo->ipo_description = $request->ipo_description;
            $ipo->upload_file = ($upload) ? $filename : "";
            $ipo->save();


            return redirect('create_ipo')->with('flash_msg', 'IPO created successfully');
        }

        return view('admin.create_ipo', $data);
    }

    public function edit_ipo(Request $request, $id) {

        $data = [];
        $data['val'] = IPO::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'ipo_title'  => 'required',
                'ipo_description'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('edit_ipo')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/ipo/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            // $id = $request->id;

            $ipo = IPO::find($id);
            $ipo->ipo_title = $request->ipo_title;
            $ipo->ipo_description = $request->ipo_description;
            if($upload) {
                $ipo->upload_file = $filename;
            }
            $ipo->save();


            return redirect('manage_ipo')->with('flash_msg', 'IPO updated successfully');
        }

        return view('admin.edit_ipo', $data);
    }

    public function delete_ipo($id) {
        $id = $id;
        IPO::where('id', $id)->delete();
        return redirect('manage_ipo')->with('flash_msg', 'IPO deleted successfully');
    }
    // -- end ipo panel

    public function director_profile_data() {
        $data = [];
        $data['get_record'] = BoardOfDirector::all();
        return view('admin.director_profile_data', $data);
    }

    /*public function about_management_profile() {
        $data = [];
        return view('admin/about_management_profile', $data);
    }*/

    public function about_contact_us() {
        $data = [];
        $data['all_data'] = Contact::all();
        return view('admin.about_contact_us', $data);
    }

    public function about_add_contact_us(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'branch_name'   => 'required',
                'phone'         => 'required',
                'email'         => 'required',
                'address'   => 'required',
                'map_link'  => 'required',
            ]);

            $contact = new Contact;
            $contact->branch_name = $request->branch_name;
            $contact->phone = $request->phone;
            $contact->fax = $request->fax;
            $contact->whats_app = $request->whats_app;
            $contact->email = $request->email;
            $contact->address = $request->address;
            $contact->map_link = $request->map_link;
            // $contact->created_by = Auth::user()->id;
            $contact->save();


            return redirect('about_add_contact_us')->with('flash_msg', 'Contact added successfully');
        }

        return view('admin.about_add_contact_us', $data);
    }

    public function edit_contact(Request $request, $id) {
        $data = [];
        $data['data'] = Contact::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'branch_name'   => 'required',
                'phone'         => 'required',
                'email'         => 'required',
                'address'   => 'required',
                'map_link'  => 'required',
            ]);

            $contact = Contact::find($id);
            $contact->branch_name = $request->branch_name;
            $contact->phone = $request->phone;
            $contact->fax = $request->fax;
            $contact->whats_app = $request->whats_app;
            $contact->email = $request->email;
            $contact->address = $request->address;
            $contact->map_link = $request->map_link;
            // $contact->created_by = Auth::user()->id;
            $contact->save();


            return redirect('about_contact_us')->with('flash_msg', 'Contact edited successfully');
        }

        return view('admin.edit_contact', $data);
    }

    public function market_data_news(Request $request) {
        $from_date = date('Y-m-d 00:00:01', strtotime($request->from_date));
        $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
        $news_data= MAN::limit(100)->orderBy('MAN_ANNOUNCEMENT_DATE_TIME', 'desc')->get();
        $total_news_data= MAN::count();
        $total_category_data= Category::count();
        $total_industry_data= Industry::count();
        $total_events_data= Event::count();
        $total_industryData=IndustryData::count();
        //dd($total_news_data);

        if ($request->isMethod('post')) {
            if ($from_date || $to_date) {
                //dd($from_date);
                $news_data = DB::table('MAN')
                    ->WhereBetween('MAN.MAN_ANNOUNCEMENT_DATE_TIME', [$from_date, $to_date])
                    ->limit(100)
                    ->get();
                //dd($news_data);
                return view('admin.market_data_news', compact('news_data','total_news_data','total_category_data','total_industry_data','total_events_data','total_industryData'));
            }
        }

        return view('admin.market_data_news', compact('news_data','total_news_data','total_category_data','total_industry_data','total_events_data','total_industryData'));
    }

    public function edit_market_category(Request $request) {
        if($request->ajax()) {

            $category = Category::find($request->edit_id);
            $category->category_name = $request->category_name;
            $category->mature_share_duration = $request->mature_share_duration;
            $category->save();

            echo "Category updated successfully";
            return;

        }
    }

    public function market_category(Request $request) {
        $data = [];
        $get_record = Category::all();
        $total_news_data= MAN::count();
        $total_events_data= Event::count();
        $total_category_data= Category::count();
        $total_industry_data= Industry::count();
        $total_industryData=IndustryData::count();
        if($request->ajax()) {
            $category = new Category;
            $category->category_name = $request->category_name;
            $category->mature_share_duration = $request->mature_share_duration;
            $category->save();
            return redirect('market_category')->with('success', 'Category inserted successfully');
        }
        return view('admin.market_category', compact('get_record','total_news_data','total_events_data','total_category_data','total_industry_data','total_industryData'));
    }

    public function delete_market_category($id) {
        // $id = $id;
        // Category::where('id', $id)->delete();
        // return redirect('market_category')->with('flash_msg', 'Category deleted successfully');
        $categories=Category::find($id);
        if ($categories){
            $categories->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
    }

    public function edit_industry_data(Request $request,$id) {
        $industry = Industry::find($id);
        $industry->industry_name = $request->industry_name;
        $industry->save();
        return redirect()->back()->with('success', 'Industry data updated successfully');
    }

    public function industry_data() {
        $data = [];
        $get_record = Industry::all();
        $total_news_data= MAN::count();
        $total_events_data= Event::count();
        $total_category_data= Category::count();
        $total_industry_data= Industry::count();
        $total_industryData=IndustryData::count();
        return view('admin.industry_data', compact('total_news_data','total_events_data','total_category_data','get_record','total_industry_data','total_industryData'));
    }

    public function create_industry_data(Request $request){
        $industry = new Industry;
        $industry->industry_name = $request->industry_name;
        $industry->save();
        return redirect()->back()->with('success', 'Industry data inserted successfully');
    }

    public function delete_industry_data($id) {
        $industryes=Industry::find($id);
        if ($industryes){
            $industryes->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
    }

    public function edit_company_data(Request $request,$id) {
            $industry = IndustryData::find($id);
            $industry->INDUSTRY_NAME = $request->industry_name;
            $industry->COMPANY_CODE = $request->company_code;
            $industry->COMPANY_NAME = $request->company_name;
            $industry->CATEGORY = $request->category;
            dd($industry);
            $industry->save();
            return redirect()->back()->with('success', 'Company data updated successfully');
    }

    public function company_data() {
        $data = [];
        $get_record = IndustryData::all();
        $cat_data = Category::all();
        $ind_data = Industry::all();
        $total_news_data= MAN::count();
        $total_events_data= Event::count();
        $total_category_data= Category::count();
        $total_industry_data= Industry::count();
        $total_industryData= IndustryData::count();
        //dd($cat_data);
        return view('admin.company_data', compact('total_news_data','total_events_data','total_category_data','total_industry_data','total_industryData','get_record','cat_data','ind_data'));
    }

    public function create_company_data(Request $request){
        $industry = new IndustryData;
        $industry->INDUSTRY_NAME = $request->industry_name;
        $industry->COMPANY_CODE = $request->company_code;
        $industry->COMPANY_NAME = $request->company_name;
        $industry->CATEGORY = $request->category;
        $industry->STATUS = 1;
        $industry->save();
        return redirect()->back()->with('success', 'Company data inserted successfully');
    }

    public function delete_company_data($id) {
        $industryDataAll=IndustryData::find($id);
        if ($industryDataAll){
            $industryDataAll->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
    }

    public function add_news_data(Request $request) {
        $total_news_data= MAN::count();
        $data = [];
        $action = $request->submit;
        if($action == "Save change") {
            $this->validate($request, [
                'trading_code'   => 'required',
                'upload_file'         => 'required',
                'news_title'         => 'required',
                'news_description'   => 'required'
            ]);

            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('about_management_profile')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/assets/company_logo/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $contact = new News;
            $contact->trading_code = $request->trading_code;
            $contact->news_title = $request->news_title;
            $contact->company_logo = $filename;
            $contact->news_description = $request->news_description;
            $contact->posted_at = date("Y-m-d");
            $contact->save();
            return redirect()->route('market_data_news')->with('success','Contact added successfully!');
        }
        return view('admin.add_news_data', compact('data','total_news_data'));
    }

    public function delete_news($id) {
        $id = $id;
        News::where('id', $id)->delete();
        return redirect('market_data_news')->with('flash_msg', 'News deleted successfully');
    }

    public function market_data_events() {
        $total_events_data= Event::count();
        $total_news_data= MAN::count();
        $total_category_data= Category::count();
        $total_industry_data= Industry::count();
        $total_industryData= IndustryData::count();
        $data = [];
        $events = Event::all();
        return view('admin.market_data_events',compact('events','total_events_data','total_news_data','total_category_data','total_industry_data','total_industryData'));
    }

    public function create_market_data_events(Request $request) {
            $event = new Event;
            $event->category = $request->category;
            $event->trading_code = $request->trading_code;
            $event->year_end = date("Y-m-d");
            $event->divident_in = $request->divident_in;
            $event->vanue = $request->vanue;
            $event->time = $request->time;
            // $event->created_by = Auth::user()->id;
            $event->save();
            return redirect()->back()->with('success','Event data ceeated successfully!');
    }

    public function delete_events($id) {
        $id = $id;
        $events=Event::find($id);
        if ($events){
            $events->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
        //return redirect('market_data_events')->with('flash_msg', 'Event deleted successfully');
    }

    public function market_commentry_delete($id) {
        $id = $id;
        MarketCommentry::where('id', $id)->delete();
        return redirect('market_commentry_data')->with('flash_msg', 'Market commentry deleted successfully');
    }

    public function equity_research_delete($id) {
        $id = $id;
        EquityResearch::where('id', $id)->delete();
        return redirect('equity_research_data')->with('flash_msg', 'Equity research data deleted successfully');
    }

    public function economic_update_delete($id) {
        $id = $id;
        EconomicUpdate::where('id', $id)->delete();
        return redirect('economic_update_data')->with('flash_msg', 'Economic update deleted successfully');
    }

    public function delete_director_profile($id) {
        $id = $id;
        BoardOfDirector::where('id', $id)->delete();
        return redirect('director_profile_data')->with('flash_msg', 'Profile deleted successfully');
    }

    public function delete_management_profile($id) {
        $id = $id;
        ManagementProfile::where('id', $id)->delete();
        return redirect('management_profile_data')->with('flash_msg', 'Profile deleted successfully');
    }

    public function delete_contact($id) {
        $id = $id;
        Contact::where('id', $id)->delete();
        return redirect('about_contact_us')->with('flash_msg', 'Contact deleted successfully');
    }
    
    // research
    // public function market_commentry_data(Request $request) {
    //     if($request->ajax()) {

    //         $market = new MarketCommentry;
    //         $market->trading_code = $request->trading_code;
    //         $market->title = $request->title;
    //         $market->description = $request->description;
    //         $market->posted_at = date("Y-m-d", strtotime($request->year_end));
    //         // $market->created_by = Auth::user()->id;
    //         $market->save();

    //         echo "Market commentry data inserted successfully";
    //         return;

    //     }
    //     $data = [];
    //     $data['get_record'] = MarketCommentry::all();
    //     return view('admin/market_commentry_data', $data);
    // }

    // public function add_market_commentry_data(Request $request) {
    //     $data = [];
    //     $action = Input::get('submit');

    //     if($action == "Save change") {
    //         $this->validate($request, [
    //             'upload_file'   => 'required',
    //             'trading_code'  => 'required',
    //             'title'   => 'required',
    //             'description'   => 'required',
    //             'posted_at'  => 'required'
    //         ]);
    //         $upload = $request->file('upload_file');
    //         if($upload) {
    //             $filename = $_FILES['upload_file']['name'];
    //             $ext = pathinfo($filename, PATHINFO_EXTENSION);
    //             $accept_files = ["pdf", "doc", "xlsx", "bmp", "gif", "docx"];
    //             if(!in_array($ext, $accept_files)) {
    //                 return redirect()->route('add_market_commentry_data')
    //                 ->with('flash_msg', 'Invalid file extension. permitted file is .pdf, .docx, .doc & .xls');
    //             }
    //             // get the file
    //             $upload = $request->file('upload_file');
    //             $filePath = $upload->getRealPath();
    //             $destination = public_path() ."/custom_files/market_commentry/". $filename;
    //             move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
    //         }

    //         $market = new MarketCommentry;
    //         $market->trading_code = $request->trading_code;
    //         $market->title = $request->title;
    //         $market->description = $request->description;
    //         $market->upload_file = ($upload) ? $filename : "";
    //         $market->posted_at = date("Y-m-d", strtotime($request->posted_at));
    //         $market->save();


    //         return redirect('market_commentry_data')->with('flash_msg', 'Data inserted successfully');
    //     }

    //     return view('admin/add_market_commentry_data', $data);
    // }

    // public function edit_market_commentry_data(Request $request, $id) {
    //     $data = [];
    //     $data['val'] = MarketCommentry::where('id', $id)->first();
    //     $action = Input::get('submit');

    //     if($action == "Save change") {
    //         $this->validate($request, [
    //             // 'upload_file'   => 'required',
    //             'trading_code'  => 'required',
    //             'title'   => 'required',
    //             'description'   => 'required',
    //             'posted_at'  => 'required'
    //         ]);
    //         $upload = $request->file('upload_file');
    //         if($upload) {
    //             $filename = $_FILES['upload_file']['name'];
    //             $ext = pathinfo($filename, PATHINFO_EXTENSION);
    //             $accept_files = ["pdf", "doc", "xlsx", "bmp", "gif", "docx"];
    //             if(!in_array($ext, $accept_files)) {
    //                 return redirect()->back()
    //                 ->with('flash_msg', 'Invalid file extension. permitted file is .pdf, .docx, .doc & .xls');
    //             }
    //             // get the file
    //             $upload = $request->file('upload_file');
    //             $filePath = $upload->getRealPath();
    //             $destination = public_path() ."/custom_files/market_commentry/". $filename;
    //             move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
    //         }

    //         $market = MarketCommentry::find($id);
    //         $market->trading_code = $request->trading_code;
    //         $market->title = $request->title;
    //         $market->description = $request->description;
    //         if($upload) {
    //             $market->upload_file = $filename;
    //         }
    //         $market->posted_at = date("Y-m-d", strtotime($request->posted_at));
    //         $market->save();


    //         return redirect('market_commentry_data')->with('flash_msg', 'Data updated successfully');
    //     }

    //     return view('admin/edit_market_commentry_data', $data);
    // }

    public function add_equity_research_data(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required',
                'category'  => 'required',
                'question'   => 'required',
                'answer'   => 'required',
                'posted_at'  => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["pdf", "doc", "xlsx", "bmp", "gif", "docx"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('add_equity_research_data')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .pdf, .docx, .doc & .xls');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/equity_research/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }

            $equity = new EquityResearch;
            $equity->category = $request->category;
            $equity->question = $request->question;
            $equity->answer = $request->answer;
            $equity->upload_file = ($upload) ? $filename : "";
            $equity->posted_at = date("Y-m-d", strtotime($request->posted_at));
            $equity->save();

            return redirect('equity_research_data')->with('flash_msg', 'Data inserted successfully');
        }

        return view('admin.add_equity_research_data', $data);
    }

    public function edit_equity_research_data(Request $request, $id) {
        $data = [];
        $data['val'] = EquityResearch::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'category'  => 'required',
                'question'   => 'required',
                'answer'   => 'required',
                'posted_at'  => 'required'
            ]);
            $upload = $request->file('upload_file');
            // $red = 'edit_equity_research_data/'.$data['val']->id;
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["pdf", "doc", "xlsx", "bmp", "gif", "docx"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->back()
                    ->with('flash_msg', 'Invalid file extension. permitted file is .pdf, .docx, .doc & .xls');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/equity_research/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            $id = $data['val']->id;
            $equity = EquityResearch::find($id);
            $equity->category = $request->category;
            $equity->question = $request->question;
            $equity->answer = $request->answer;
            if($upload) {
                $equity->upload_file = $filename;
            }
            $equity->posted_at = date("Y-m-d", strtotime($request->posted_at));
            $equity->save();

            return redirect('equity_research_data')->with('flash_msg', 'Data updated successfully');
        }

        return view('admin.edit_equity_research_data', $data);
    }

    public function equity_research_data(Request $request) {
        $data = [];
        if($request->ajax()) {

            $market = new EquityResearch;
            $market->category = $request->category;
            $market->question = $request->question;
            $market->answer = $request->answer;
            $market->posted_at = date("Y-m-d", strtotime($request->year_end));
            // $market->created_by = Auth::user()->id;
            $market->save();

            echo "Equity research data inserted successfully";
            return;

        }
        $data['get_record'] = EquityResearch::all();
        return view('admin.equity_research_data', $data);
    }

    public function economic_update_data(Request $request) {
        $data = [];
        if($request->ajax()) {

            $economicUpdate = new EconomicUpdate;
            $economicUpdate->category = $request->category;
            $economicUpdate->title = $request->title;
            $economicUpdate->description = $request->description;
            $economicUpdate->posted_at = date("Y-m-d", strtotime($request->year_end));
            // $economicUpdate->created_by = Auth::user()->id;
            $economicUpdate->save();

            echo "Economic update data inserted successfully";
            return;

        }
        $data['get_record'] = EconomicUpdate::all();
        return view('admin.economic_update_data', $data);
    }

    public function add_economic_update_data(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required',
                'category'  => 'required',
                'title'   => 'required',
                'description'   => 'required',
                'posted_at'  => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["pdf", "doc", "xlsx", "bmp", "gif", "docx"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('add_economic_update_data')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .pdf, .docx, .doc & .xls');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/economic_update/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }

            $economy = new EconomicUpdate;
            $economy->category = $request->category;
            $economy->title = $request->title;
            $economy->description = $request->description;
            $economy->upload_file = ($upload) ? $filename : "";
            $economy->posted_at = date("Y-m-d", strtotime($request->posted_at));
            $economy->save();

            return redirect('economic_update_data')->with('flash_msg', 'Data inserted successfully');
        }

        return view('admin.add_economic_update_data', $data);
    }

    public function edit_economic_update_data(Request $request, $id) {
        $data = [];
        $data['val'] = EconomicUpdate::where('id', $id)->first();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                // 'upload_file'   => 'required',
                'category'  => 'required',
                'title'   => 'required',
                'description'   => 'required',
                'posted_at'  => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["pdf", "doc", "xlsx", "bmp", "gif", "docx"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->back()
                    ->with('flash_msg', 'Invalid file extension. permitted file is .pdf, .docx, .doc & .xls');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/economic_update/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }

            $economy = EconomicUpdate::find($id);
            $economy->category = $request->category;
            $economy->title = $request->title;
            $economy->description = $request->description;
            if($upload) {
                $economy->upload_file = $filename;
            }
            $economy->posted_at = date("Y-m-d", strtotime($request->posted_at));
            $economy->save();

            return redirect('economic_update_data')->with('flash_msg', 'Data updated successfully');
        }

        return view('admin.edit_economic_update_data', $data);
    }

    public function all_stock_order(Request $request) {

        if($request->ajax()) {
            $stock_id = $request->stock_id;
            $order_status = $request->order_status;
            $order_type = $request->order_type;

            $order_data = OrderManagement::where('id', $stock_id)->first();
            $cancel_data = $order_data;
            $total_amount = $order_data->total_amount;
            $client_code = $order_data->client_code;

            // - 1=Pending, 2=Submitted, 3=Rejected, 4=Cancel, 5=Executed, 6=Canceled By User

            if($order_status == 5) {

                if($order_type == "buy") {
                    // DB::select("UPDATE client_limits SET cash=cash-{$total_amount} WHERE clientcode='{$client_code}'");
                }
                else if($order_type == "sell") {
                    DB::select("UPDATE client_limits SET cash=cash+{$total_amount} WHERE clientcode='{$client_code}'");
                }
            }
            else if($order_status == 3) {
                if($order_type == "buy") {
                    DB::select("UPDATE client_limits SET cash=cash+{$total_amount} WHERE clientcode='{$client_code}'");
                }
                else if($order_type == "sell") {
                    DB::select("UPDATE batch_data SET quantity=quantity+{$cancel_data->number_of_share}, total_cost=total_cost+{$cancel_data->total_amount} WHERE client_code='{$cancel_data->client_code}' AND security_code='{$cancel_data->security_code}'");
                }
            }
            else if($order_status == 4) {
                if($order_type == "buy") {
                    DB::select("UPDATE client_limits SET cash=cash+{$total_amount} WHERE clientcode='{$client_code}'");
                }
                else if($order_type == "sell") {
                    DB::select("UPDATE batch_data SET quantity=quantity+{$cancel_data->number_of_share}, total_cost=total_cost+{$cancel_data->total_amount} WHERE client_code='{$cancel_data->client_code}' AND security_code='{$cancel_data->security_code}'");
                }
            }

            $order = OrderManagement::find($stock_id);
            $order->order_status = $order_status;
            $order->save();
            return redirect()->back()->with('success','Status updated successfully, Thank you');
        }

        $client_code = session('client_code');
        $data = [];
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        $bubu = date("Y-m-d");
        $get_data = DB::select("SELECT * FROM order_management WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
        return view('admin.all_stock_order', compact('get_data'));
    }

    public function all_stock_order_data() {
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        $str = "";
        $get_data = DB::select("SELECT om.*, u.name FROM order_management AS om INNER JOIN users AS u ON om.user_id=u.id WHERE om.created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
        
        $str .= "<table class='table table-hover'>";
        $str .= "<tr><th>SL</th><th>User Name</th><th>Order Type</th><th>Market</th><th>Secutiry Code</th><th>Client Code</th><th>Current Rate</th><th>Order Rate</th><th>B.O Account</th><th>Number of Share</th><th>Total Amount</th><th>Request Date</th><th>Status</th><th>From</th><th>Action</th></tr>";
        $i=1;
        foreach($get_data as $val) {

            $str .= "<tr>";
                $str .= "<td>" . $i . "</td>";
                $str .= "<td>" . $val->name . "</td>";
                $str .= "<td>";
                    if($val->order_type == "buy") :
                        $str .= "<label class='label label-success'>". $val->order_type ."</label>";
                    elseif($val->order_type == "sell") :
                        $str .= "<label class='label label-warning'>". $val->order_type . "</label>";
                    endif;
                $str .= "</td>";
                $str .= "<td>" . $val->market_type . "</td>";
                $str .= "<td>" . $val->security_code . "</td>";
                $str .= "<td>" . $val->client_code . "</td>";
                $str .= "<td>" . $val->current_rate . "</td>";
                $str .= "<td>" . $val->order_rate . "</td>";
                $str .= "<td>" . $val->bo_account . "</td>";
                $str .= "<td>" . $val->number_of_share . "</td>";
                $str .= "<td>" . $val->number_of_share*$val->order_rate . "</td>";
                $str .= "<td>" . date("jS F Y", strtotime($val->created_at)) . "</td>";
                $str .= "<td>";

                    if($val->order_status == 1) :
                    $str .= "<label class='label label-danger'>Pending</label>";
                    elseif($val->order_status == 2) :
                    $str .= "<label class='label label-primary'>Submitted</label>";
                    elseif($val->order_status == 3) :
                    $str .= "<label class='label label-warning'>Rejected</label>";
                    elseif($val->order_status == 4) :
                    $str .= "<label class='label label-warning'>Cancel</label>";
                    elseif($val->order_status == 5) :
                    $str .= "<label class='label label-success'>Executed</label>";
                    elseif($val->order_status == 6) :
                    $str .= "<label class='label label-danger'>Canceled By User</label>";
                    endif;
                $str .= "</td>";
                $str .= "<td>";
                    if($val->flag == 'WEB') :
                    $str .= "<label class='label label-success'>WEB</label>";
                    elseif($val->flag == 'APP') :
                    $str .= "<label class='label label-primary'>APP</label>";
                    endif;
                $str .= "</td>";
                $str .= "<td>";
                    if( ($val->order_status==1) || ($val->order_status==2) || ($val->order_status==3) || ($val->order_status==4) ) :
                        $str .= "<a class='change_stock_status btn btn-primary btn-xs' data-id='{$val->id}' data-order-type='{$val->order_type}' data-status='{$val->order_status}' href=''>Change Status</a>";
                    endif;
                $str .= "</td>";
            $str .= "</tr>";

            $i++;
        }
        $str .= "</table>";

        echo $str;

    }

    // -- stock order
    public function stock_order_report(Request $request) {

        if($request->ajax()) {
            $stock_id = $request->stock_id;
            $order_status = $request->order_status;
            $order_type = $request->order_type;

            $order_data = OrderManagement::where('id', $stock_id)->first();
            $cancel_data = $order_data;
            $total_amount = $order_data->total_amount;
            $client_code = $order_data->client_code;

            // - 1=Pending, 2=Submitted, 3=Rejected, 4=Cancel, 5=Executed, 6=Canceled By User

            if($order_status == 5) {

                if($order_type == "buy") {
                    // DB::select("UPDATE client_limits SET cash=cash-{$total_amount} WHERE clientcode='{$client_code}'");
                }
                else if($order_type == "sell") {
                    DB::select("UPDATE client_limits SET cash=cash+{$total_amount} WHERE clientcode='{$client_code}'");
                }
            }
            else if($order_status == 3) {
                if($order_type == "buy") {
                    DB::select("UPDATE client_limits SET cash=cash+{$total_amount} WHERE clientcode='{$client_code}'");
                }
                else if($order_type == "sell") {
                    DB::select("UPDATE batch_data SET quantity=quantity+{$cancel_data->number_of_share}, total_cost=total_cost+{$cancel_data->total_amount} WHERE client_code='{$cancel_data->client_code}' AND security_code='{$cancel_data->security_code}'");
                }
            }
            else if($order_status == 4) {
                if($order_type == "buy") {
                    DB::select("UPDATE client_limits SET cash=cash+{$total_amount} WHERE clientcode='{$client_code}'");
                }
                else if($order_type == "sell") {
                    DB::select("UPDATE batch_data SET quantity=quantity+{$cancel_data->number_of_share}, total_cost=total_cost+{$cancel_data->total_amount} WHERE client_code='{$cancel_data->client_code}' AND security_code='{$cancel_data->security_code}'");
                }
            }

            $order = OrderManagement::find($stock_id);
            $order->order_status = $order_status;
            $order->save();
            return redirect()->back()->with('success','Status updated successfully, Thank you');
        }


        $action = $request->submit;
        if($action == "Submit") {
            $from = date("Y-m-d 00:00:00", strtotime($request->from_date));
            $to = date("Y-m-d 23:59:59", strtotime($request->to_date));

            Log::info("SELECT * FROM order_management WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");

            $get_data = DB::table('order_management')
                    ->leftJoin('users', 'order_management.user_id', '=', 'users.id')
                    ->select('order_management.*', 'users.name as user_name')
                    ->WhereBetween('order_management.created_at', [$from, $to])
                    ->orderBy('id','DESC')
                    ->get();

            //$get_data =DB::select("SELECT * FROM order_management WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
            return view('admin.stock_order_report', compact('get_data'));
        }


        $client_code = session('client_code');
        $data = [];
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        $bubu = date("Y-m-d");
        //$get_data =DB::select("SELECT * FROM order_management WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
        $get_data = $get_data = DB::table('order_management')
                    ->leftJoin('users', 'order_management.user_id', '=', 'users.id')
                    ->select('order_management.*', 'users.name as user_name')
                    ->WhereBetween('order_management.created_at', [$from, $to])
                    ->orderBy('id','DESC')
                    ->get();
        return view('admin.stock_order_report', compact('get_data'));
    }
    // -- end stock order

    public function circuit_breaker_data(Request $request) {
        $data = [];
        $dse_data = CircuitBreaker::where('breaker_type', 'dse')->get();
        $total_dse_data=CircuitBreaker::where('breaker_type', 'dse')->count();
        $total_cse_data=CircuitBreaker::where('breaker_type', 'cse')->count();
        if($request->ajax()) {

            if($request->page_type == "edit_user_get") {
                $user_data = User::where('id', $request->user_id)->first();
                return json_encode($user_data);
            }
            else if($request->page_type == "add") {

                $dse = new CircuitBreaker;
                $dse->breaker_type = "dse";
                $dse->range_start = $request->range_start;
                $dse->range_end = $request->range_end;
                $dse->breaker_value = $request->breaker_value;
                $dse->save();
                return redirect('circuit_breaker_data')->with('success', 'Circuit Breaker created successfully');
            }
            else if($request->page_type == "edit") {
                $dse = CircuitBreaker::find($request->edit_id);
                $dse->breaker_type = "dse";
                $dse->range_start = $request->range_start;
                $dse->range_end = $request->range_end;
                $dse->breaker_value = $request->breaker_value;
                $dse->save();
                return redirect('circuit_breaker_data')->with('success', 'Circuit Breaker updated successfully');
            }
        }
        return view('admin.circuit_breaker_data', compact('dse_data','total_dse_data','total_cse_data'));
    }

    public function delete_circuit_breaker_data($id) {
        $circuitData=CircuitBreaker::find($id);
        if ($circuitData){
            $circuitData->delete();
            return response()->json('success',201);
        }else{
            return response()->json('error',422);
        }
    }

    public function circuit_breaker_data_cse(Request $request) {
        $data = [];
        $cse_data= CircuitBreaker::where('breaker_type', 'cse')->get();
        $total_dse_data=CircuitBreaker::where('breaker_type', 'dse')->count();
        $total_cse_data=CircuitBreaker::where('breaker_type', 'cse')->count();
        if($request->ajax()) {

            if($request->page_type == "edit_user_get") {
                $user_data = User::where('id', $request->user_id)->first();
                return json_encode($user_data);
            }
            else if($request->page_type == "add") {

                $dse = new CircuitBreaker;
                $dse->breaker_type = "cse";
                $dse->range_start = $request->range_start;
                $dse->range_end = $request->range_end;
                $dse->breaker_value = $request->breaker_value;
                $dse->save();
                return redirect('circuit_breaker_data_cse')->with('success', 'Circuit Breaker created successfully');
            }
            else if($request->page_type == "edit") {
                $dse = CircuitBreaker::find($request->edit_id);
                $dse->breaker_type = "cse";
                $dse->range_start = $request->range_start;
                $dse->range_end = $request->range_end;
                $dse->breaker_value = $request->breaker_value;
                $dse->save();
                return redirect('circuit_breaker_data_cse')->with('success', 'Circuit Breaker updated successfully');
            }
        }
        return view('admin.circuit_breaker_data_cse', compact('cse_data','total_dse_data','total_cse_data'));
    }

    public function delete_circuit_breaker_data_cse($id) {

        $id = $id;
        CircuitBreaker::where('id', $id)->delete();
        return redirect('circuit_breaker_data_cse')->with('flash_msg', 'Data deleted successfully');

    }

    public function open_bo_account(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save Account") {

            $bo_account = new UserBOAccountData;
            $bo_account->bo_identification_number = $request->bo_identification_number;
            $bo_account->bo_type = $request->bo_type;
            $bo_account->bo_category = $request->bo_category;
            $bo_account->dp_internal_reference_number = $request->dp_internal_reference_number;
            $bo_account->name_of_first_holder = $request->name_of_first_holder;
            $bo_account->second_joint_holder = $request->second_joint_holder;
            $bo_account->third_joint_holder = $request->third_joint_holder;
            $bo_account->contact_person_name = $request->contact_person_name;
            $bo_account->sex_code = $request->sex_code;
            $bo_account->date_of_birth = date("Y-m-d", strtotime($request->date_of_birth));
            $bo_account->registration_number = $request->registration_number;
            $bo_account->father_or_husband_name = $request->father_or_husband_name;
            $bo_account->mother_name = $request->mother_name;
            $bo_account->occupation = $request->occupation;
            $bo_account->residency_flag = $request->residency_flag;
            $bo_account->nationality = $request->nationality;
            $bo_account->address_1 = $request->address_1;
            $bo_account->address_2 = $request->address_2;
            $bo_account->address_3 = $request->address_3;
            $bo_account->city = $request->city;
            $bo_account->state = $request->state;
            $bo_account->country = $request->country;
            $bo_account->postal_code = $request->postal_code;
            $bo_account->phone_number = $request->phone_number;
            $bo_account->email_id = $request->email_id;
            $bo_account->fax_number = $request->fax_number;
            $bo_account->statement_cycle_code = $request->statement_cycle_code;
            $bo_account->bo_short_name = $request->bo_short_name;
            $bo_account->second_holder_short_name = $request->second_holder_short_name;
            $bo_account->third_holder_short_name = $request->third_holder_short_name;
            $bo_account->passport_number = $request->passport_number;
            $bo_account->passport_issue_date = date("Y-m-d", strtotime($request->passport_issue_date));
            $bo_account->passport_expiry_date = date("Y-m-d", strtotime($request->passport_expiry_date));
            $bo_account->passport_issue_place = $request->passport_issue_place;
            $bo_account->bank_name = $request->bank_name;
            $bo_account->bank_branch_name = $request->bank_branch_name;
            $bo_account->bank_account_number = $request->bank_account_number;
            $bo_account->electronic_dividend_flag = $request->electronic_dividend_flag;
            $bo_account->tax_exemption_flag = $request->tax_exemption_flag;
            $bo_account->tax_identification_number = $request->tax_identification_number;
            $bo_account->exchange_id = $request->exchange_id;
            $bo_account->trading_id = $request->trading_id;
            $bo_account->bank_routine_number = $request->bank_routine_number;
            $bo_account->bank_identification_code = $request->bank_identification_code;
            $bo_account->international_bank_account_number = $request->international_bank_account_number;
            $bo_account->bank_swift_code = $request->bank_swift_code;
            $bo_account->first_holder_national_id = $request->first_holder_national_id;
            $bo_account->second_holder_national_id = $request->second_holder_national_id;
            $bo_account->third_holder_national_id = $request->third_holder_national_id;
            $bo_account->save();

            return redirect('manage_bo_account')->with('flash_msg', 'B.O Account Created Successfully');
        }

        return view('admin.open_bo_account', $data);
    }

    public function manage_bo_account(Request $request) {
        if($request->ajax()) {
            $bo_account_id = $request->account_id;
            $user_id = $request->user_id;

            $account = UserBOAccountData::find($bo_account_id);
            $account->user_id = $user_id;
            $account->save();
            return;
        }
        $data = [];
        $action = Input::get('submit');
        $all_data = DB::select("SELECT id, (SELECT COUNT(*) FROM users WHERE role=1) AS TOT_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Free') AS FREE_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Premium') AS PREMIUM_USER, (SELECT COUNT(*) FROM users WHERE role=0) AS ADMIN_USER FROM users LIMIT 1");
        $data['all_data'] = $all_data[0];
        $data['get_data'] = UserBOAccountData::paginate(10);

        if($action == "Submit") {
            $name = $request->name;
            $email = $request->email;
            $client_code = $request->client_code;
        

            if(!$name && !$email && !$client_code) {
                return redirect('manage_bo_account')->with('flash_msg', 'Please input any one field');
            }

            if($name) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE bo_short_name LIKE '%{$name}%'");
            }
            else if($email) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE email_id='{$email}'");
            }
            else if($client_code) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE dp_internal_reference_number='{$client_code}'");
            }
            else if($name && $email) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE bo_short_name LIKE '%{$name}%' AND email_id='{$email}'");
            }
            else if($name && $client_code) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE bo_short_name LIKE '%{$name}%' AND dp_internal_reference_number='{$client_code}'");
            }
            else if($clientcode && $email) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE email='{$email}' AND dp_internal_reference_number='{$client_code}'");
            }
            else if($name && $email && $client_code) {
                $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data WHERE bo_short_name LIKE '%{$name}%' AND dp_internal_reference_number='{$client_code}' AND  email='{$email}'");
            }
            // dd($data['get_data']);
        }

        

        $data['users'] = User::where('role', 1)->get();
        return view('admin.manage_bo_account', $data);
    }

    public function view_bo_account($id) {
        $data = array();
        $data['get_data'] = UserBOAccountData::where('id', $id)->first();
        // dd($data);
        return view('admin.view_bo_account', $data);
    }

    public function new_bo_account(Request $request) {
        if($request->ajax()) {
            $bo_account_id = $request->account_id;
            $user_id = $request->user_id;

            $account = UserBOAccountDataDemo::find($bo_account_id);
            $account->user_id = $user_id;
            //Log::info('Get Data = ',  $account->user_id);

            $account->save();
            return;
        }
        //Log::info('Get Data without ajax request = ');
        $data = [];
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        $all_data = DB::select("SELECT id, (SELECT COUNT(*) FROM users WHERE role=1) AS TOT_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Free') AS FREE_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Premium') AS PREMIUM_USER, (SELECT COUNT(*) FROM users WHERE role=0) AS ADMIN_USER FROM users LIMIT 1");
        $all_data = $all_data[0];
        // $data['get_data'] = DB::select("SELECT * FROM user_bo_account_data_demo WHERE created_at BETWEEN '{$from}' AND '{$to}'");
        $get_data = DB::select("SELECT * FROM user_bo_account_data ORDER BY id DESC LIMIT 100");
        //dd($data['get_data']);die;
        $users = User::where('role', 1)->get();
        return view('admin.new_bo_account', compact('all_data','get_data','users'));
    }

    public function edit_bo_account(Request $request, $id) {

        $data = [];
        $val = UserBOAccountData::where('id', $id)->first();
        $action = $request->submit;
        if($action == "Save Account") {
            $bo_account = UserBOAccountData::find($id);
            $bo_account->bo_identification_number = $request->bo_identification_number;
            $bo_account->bo_type = $request->bo_type;
            $bo_account->bo_category = $request->bo_category;
            $bo_account->dp_internal_reference_number = $request->dp_internal_reference_number;
            $bo_account->name_of_first_holder = $request->name_of_first_holder;
            $bo_account->second_joint_holder = $request->second_joint_holder;
            $bo_account->third_joint_holder = $request->third_joint_holder;
            $bo_account->contact_person_name = $request->contact_person_name;
            $bo_account->sex_code = $request->sex_code;
            $bo_account->date_of_birth = date("Y-m-d", strtotime($request->date_of_birth));
            $bo_account->registration_number = $request->registration_number;
            $bo_account->father_or_husband_name = $request->father_or_husband_name;
            $bo_account->mother_name = $request->mother_name;
            $bo_account->occupation = $request->occupation;
            $bo_account->residency_flag = $request->residency_flag;
            $bo_account->nationality = $request->nationality;
            $bo_account->address_1 = $request->address_1;
            $bo_account->address_2 = $request->address_2;
            $bo_account->address_3 = $request->address_3;
            $bo_account->city = $request->city;
            $bo_account->state = $request->state;
            $bo_account->country = $request->country;
            $bo_account->postal_code = $request->postal_code;
            $bo_account->phone_number = $request->phone_number;
            $bo_account->email_id = $request->email_id;
            $bo_account->fax_number = $request->fax_number;
            $bo_account->statement_cycle_code = $request->statement_cycle_code;
            $bo_account->bo_short_name = $request->bo_short_name;
            $bo_account->second_holder_short_name = $request->second_holder_short_name;
            $bo_account->third_holder_short_name = $request->third_holder_short_name;
            $bo_account->passport_number = $request->passport_number;
            $bo_account->passport_issue_date = date("Y-m-d", strtotime($request->passport_issue_date));
            $bo_account->passport_expiry_date = date("Y-m-d", strtotime($request->passport_expiry_date));
            $bo_account->passport_issue_place = $request->passport_issue_place;
            $bo_account->bank_name = $request->bank_name;
            $bo_account->bank_branch_name = $request->bank_branch_name;
            $bo_account->bank_account_number = $request->bank_account_number;
            $bo_account->electronic_dividend_flag = $request->electronic_dividend_flag;
            $bo_account->tax_exemption_flag = $request->tax_exemption_flag;
            $bo_account->tax_identification_number = $request->tax_identification_number;
            $bo_account->exchange_id = $request->exchange_id;
            $bo_account->trading_id = $request->trading_id;
            $bo_account->bank_routine_number = $request->bank_routine_number;
            $bo_account->bank_identification_code = $request->bank_identification_code;
            $bo_account->international_bank_account_number = $request->international_bank_account_number;
            $bo_account->bank_swift_code = $request->bank_swift_code;
            $bo_account->first_holder_national_id = $request->first_holder_national_id;
            $bo_account->second_holder_national_id = $request->second_holder_national_id;
            $bo_account->third_holder_national_id = $request->third_holder_national_id;
            $bo_account->save();

            return redirect('manage_bo_account')->with('success', 'B.O Account Updated Successfully');
        }

        return view('admin.edit_bo_account',compact('val'));

    }

    public function export_bo_account(Request $request, $id) {

        $data = [];
        $account_data = UserBOAccountData::where('id', $id)->first()->toArray();
        // dd($account_data);
        $file_name = "bo_" . $account_data['bo_identification_number'] . ".txt";

        unset($account_data['id']);
        unset($account_data['user_id']);
        unset($account_data['user_data_id']);
        unset($account_data['updated_at']);

        $papana = [];
        foreach($account_data as $key=>$val) {
            $papana[] = $val;
        }
        $raw_string = implode("~", $papana);
        // dd($raw_string);
        $handle = fopen($file_name, "w");
        fwrite($handle, $raw_string);
        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename("$file_name"));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize("$file_name"));
        readfile("$file_name");
        exit;
    }

    public function delete_bo_account($id) {
        $id = $id;
        UserBOAccountData::where('id', $id)->delete();
        return redirect('manage_bo_account')->with('success', 'Account deleted successfully');
    }

    public function upload_bo_account(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required'
            ]);
            $upload = $request->file('upload_file');
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["txt", "TXT"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('upload_bo_account')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .txt');
                }
                $papana = [];
                $lines = file($_FILES['upload_file']['tmp_name']);
                for($i=0; $i<count($lines); $i++) {
                    if($lines[$i]=="\r\n") {
                        unset($lines[$i]);
                    }
                }
                $lines = array_values($lines);
                // dd(explode("~", $lines[0]));
                
                if(strpos($lines[0], "~") === false) {
                    return redirect()->route('upload_bo_account')
                    ->with('flash_msg', 'Invalid file format');
                }
                
                /*$papana = [];
                foreach($lines as $line) {
                    $line_data = explode("~", $line);
                    if($line_data[16] == "Active") {
                        $papana[] = $line_data;
                    }
                    echo "<pre>";
                    print_r($line_data);
                    echo "<pre>";
                }
                dd(count($papana));
                die;*/
                // dd($lines);die();
                foreach($lines as $line) {
                    $line_data = explode("~", $line);

                    $chk_data = UserBOAccountData::where('bo_identification_number', $line_data[0])->get();
                    if(!count($chk_data)) {
                        $bo_account = new UserBOAccountData;
                        $bo_account->bo_identification_number = $line_data[0];
                        $bo_account->bo_type = $line_data[1];
                        $bo_account->bo_category = $line_data[2];
                        $bo_account->dp_internal_reference_number = $line_data[3];
                        $bo_account->name_of_first_holder = $line_data[4];
                        $bo_account->second_joint_holder = $line_data[5];
                        $bo_account->third_joint_holder = $line_data[6];
                        $bo_account->contact_person_name = $line_data[7];
                        $bo_account->sex_code = $line_data[8];
                        $bo_account->date_of_birth = date("Y-m-d", strtotime($line_data[9]));
                        $bo_account->registration_number = $line_data[10];
                        $bo_account->father_or_husband_name = $line_data[11];
                        $bo_account->mother_name = $line_data[12];
                        $bo_account->occupation = $line_data[13];
                        $bo_account->residency_flag = $line_data[14];
                        $bo_account->nationality = $line_data[15];
                        $bo_account->address_1 = $line_data[16];
                        $bo_account->address_2 = $line_data[17];
                        $bo_account->address_3 = $line_data[18];
                        $bo_account->city = $line_data[19];
                        $bo_account->state = $line_data[20];
                        $bo_account->country = $line_data[21];
                        $bo_account->postal_code = $line_data[22];
                        $bo_account->phone_number = $line_data[23];
                        $bo_account->email_id = $line_data[24];
                        $bo_account->fax_number = $line_data[25];
                        $bo_account->statement_cycle_code = $line_data[26];
                        $bo_account->bo_short_name = $line_data[27];
                        $bo_account->second_holder_short_name = $line_data[28];
                        $bo_account->third_holder_short_name = $line_data[29];
                        $bo_account->passport_number = $line_data[30];
                        $bo_account->passport_issue_date = date("Y-m-d", strtotime($line_data[31]));
                        $bo_account->passport_expiry_date = date("Y-m-d", strtotime($line_data[32]));
                        $bo_account->passport_issue_place = $line_data[33];
                        $bo_account->bank_name = $line_data[34];
                        $bo_account->bank_branch_name = $line_data[35];
                        $bo_account->bank_account_number = $line_data[36];
                        $bo_account->electronic_dividend_flag = $line_data[37];
                        $bo_account->tax_exemption_flag = $line_data[38];
                        $bo_account->tax_identification_number = $line_data[39];
                        $bo_account->exchange_id = $line_data[40];
                        $bo_account->trading_id = $line_data[41];
                        $bo_account->bank_routine_number = $line_data[42];
                        $bo_account->bank_identification_code = $line_data[43];
                        $bo_account->international_bank_account_number = $line_data[44];
                        $bo_account->bank_swift_code = $line_data[45];
                        $bo_account->first_holder_national_id = $line_data[46];
                        $bo_account->second_holder_national_id = $line_data[47];
                        $bo_account->third_holder_national_id = $line_data[48];
                        $bo_account->save();
                    }

                    // -- inser DP29 40 column data
                    if($line_data[16] == "Active") {

                        $bo_account = new UserBOAccountData;
                        $bo_account->bo_identification_number = $line_data[0];
                        $bo_account->bo_type = $line_data[1];
                        $bo_account->bo_category = $line_data[2];
                        $bo_account->name_of_first_holder = $line_data[3];
                        $bo_account->bo_short_name = $line_data[4];
                        $bo_account->address_1 = $line_data[5];
                        $bo_account->city = $line_data[6];
                        $bo_account->state = $line_data[7];
                        $bo_account->country = $line_data[8];
                        $bo_account->postal_code = $line_data[9];
                        $bo_account->residency_flag = $line_data[10];
                        $bo_account->phone_number = $line_data[11];
                        $bo_account->fax_number = $line_data[12];
                        $bo_account->email_id = $line_data[13];
                        $bo_account->dp_internal_reference_number = $line_data[14];
                        // -- setup date
                        // -- bo status
                        // -- closure date
                        $bo_account->father_or_husband_name = $line_data[18];
                        $bo_account->mother_name = $line_data[19];
                        $bo_account->bank_name = $line_data[20];
                        $bo_account->bank_branch_name = $line_data[21];
                        $bo_account->bank_account_number = $line_data[22];
                        $bo_account->bank_routine_number = $line_data[23];
                        $bo_account->bank_identification_code = $line_data[24];
                        $bo_account->international_bank_account_number = $line_data[25];
                        $bo_account->bank_swift_code = $line_data[26];
                        // - Col28=Suspense Flag
                        // -- Col29=Bo Suspened date
                        // -- Col30=Suspense Reason Code
                        $bo_account->second_holder_short_name = $line_data[30];
                        $bo_account->occupation = $line_data[31];
                        $bo_account->date_of_birth = date("Y-m-d", strtotime($line_data[32]));
                        $bo_account->sex_code = $line_data[33];
                        $bo_account->nationality = $line_data[34];
                        $bo_account->tax_identification_number = $line_data[35];
                        // -- orogin of B.O
                        $bo_account->first_holder_national_id = $line_data[37];
                        $bo_account->second_holder_national_id = $line_data[38];
                        $bo_account->third_holder_national_id = $line_data[39];

                        $bo_account->save();
                    }

                }
            }

            return redirect('manage_bo_account')->with('flash_msg', 'B.O account data uploaded successfully');
        }

        return view('admin.upload_bo_account', $data);
    }

    public function upload_client_limit(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required'
            ]);

            // checkings
            $filename = $_FILES['upload_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $accept_files = ["xml", "XML"];
            if(!in_array($ext, $accept_files)) {
                return redirect()->route('upload_client_limit')
                ->with('flash_msg', 'Invalid file extension. permitted file is .xml');
            }
            
            $xml_data = simplexml_load_file($_FILES['upload_file']['tmp_name']);

            $papana = "";
            $query_string = "INSERT INTO `client_limits` (`clientcode`, `cash`) VALUES ";
            $tot_count = count($xml_data);
            $i=1;
            foreach($xml_data as $xml) {

                if(!$xml->Cash) {
                    return redirect()->route('upload_client_limit')
                    ->with('flash_msg', 'Invalid file format');
                }

                if($i==$tot_count) {
                    $papana .= " ('{$xml->ClientCode}', '{$xml->Cash}')";
                }else {
                    $papana .= " ('{$xml->ClientCode}', '{$xml->Cash}'),";
                }
                $i++;
            }

            // -----------------

            $full_string = $query_string . $papana;
            DB::select("TRUNCATE client_limits");
            DB::select($full_string);
            /*foreach($xml_data as $xml) {
                if(!$xml->Cash) {
                    return redirect()->route('upload_client_limit')
                    ->with('flash_msg', 'Invalid file format');
                }
                $limits = new ClientLimits;
                $limits->clientcode = $xml->ClientCode;
                $limits->cash = $xml->Cash;
                $limits->save();
            }*/

            return redirect('upload_client_limit')->with('flash_msg', 'Clients limit data uploaded successfully');
        }

        return view('admin.upload_client_limit', $data);
    }

    public function manage_user_account(Request $request) {
        if($request->ajax()) {
            if($request->page_type == "edit_user_get") {
                $user_data = User::where('id', $request->user_id)->first();
                return json_encode($user_data);
            }
            else if($request->page_type == "add_user") {

                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->client_code = $request->client_code;
                $user->role = 1;
                $user->user_type = $request->user_type;
                $user->mobile = $request->mobile;
                $user->verified = 1;
                $user->joined_date = date("Y-m-d", strtotime($request->joined_date));
                $user->password = bcrypt('123456');
                $user->save();

                echo "User created successfully";
                return;

            }
        }
        $data = [];
        $all_data = DB::select("SELECT id, (SELECT COUNT(*) FROM users WHERE role=1) AS TOT_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Free') AS FREE_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Premium') AS PREMIUM_USER, (SELECT COUNT(*) FROM users WHERE role=0) AS ADMIN_USER FROM users LIMIT 1");
        $data['all_data'] = $all_data[0];
        $data['get_data'] = User::where('role', 1)->get();
        return view('admin.manage_user_account', $data);
    }

    public function delete_user($id) {
        $id = $id;
        User::where('id', $id)->delete();
        return redirect('manage_user_account')->with('flash_msg', 'User deleted successfully');
    }

    public function edit_user(Request $request, $id) {

        $data = [];
        $data['get_data'] = User::where('id', $id)->first();
        $action = Input::get('submit');
        if($action == "Update Profile") {

            $upload = $request->file('upload_file');
            $upload2 = $request->file('upload_file2');

            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('manage_user_account')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/user/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            if($upload2) {
                $filename2 = $_FILES['upload_file2']['name'];
                $ext = pathinfo($filename2, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('manage_user_account')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload2 = $request->file('upload_file2');
                $filePath = $upload2->getRealPath();
                $destination = public_path() ."/custom_files/signature/". $filename2;
                move_uploaded_file($_FILES['upload_file2']['tmp_name'], $destination);
            }

            $edit_user = User::find($id);
            $edit_user->name = $request->name;
            $edit_user->client_code = $request->client_code;
            $edit_user->user_type = $request->user_type;
            $edit_user->mobile = $request->mobile;
            $edit_user->image = ($upload) ? $filename : $data['get_data']->image;
            $edit_user->signature = ($upload2) ? $filename2 : $data['get_data']->signature;
            $edit_user->save();

            return redirect('manage_user_account')->with('flash_msg', 'Profile updated successfully');
        }
        return view('admin.edit_user', $data);

    }

    public function ban_user($id) {
        $x = User::find($id);
        $x->verified = 0;
        $x->save();
        return redirect('manage_user_account')->with('flash_msg', 'Account banned successfully');
    }

    public function unban_user($id) {
        $x = User::find($id);
        $x->verified = 1;
        $x->save();
        return redirect('manage_user_account')->with('flash_msg', 'Account activate successfully');
    }

    public function upload_batch_data(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            // $this->validate($request, [
            //     'upload_file'   => 'required'
            // ]);

            // checkings
            // $tm = $_FILES['upload_file']['tmp_name'];
            // print_r($tm);die();
            // $err = $_FILES['upload_file']['error'];
            // print_r($err);die();
            $filename = $_FILES['upload_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $accept_files = ["xml", "XML"];
            if(!in_array($ext, $accept_files)) {
                return redirect()->route('upload_batch_data')
                ->with('flash_msg', 'Invalid file extension. permitted file is .xml');
            }
            
            $xml_data = simplexml_load_file($_FILES['upload_file']['tmp_name']);
            // print_r($xml_data);die();
            $papana = "";
            $query_string = "INSERT INTO `batch_data` (`client_code`, `security_code`, `isin`, `quantity`, `total_cost`) VALUES ";
            $tot_count = count($xml_data);
            $i=1;
            foreach($xml_data as $xml) {

                if(!$xml->SecurityCode) {
                    return redirect()->route('upload_batch_data')
                    ->with('flash_msg', 'Invalid file format');
                }

                if($i==$tot_count) {
                    $papana .= " ('{$xml->ClientCode}', '{$xml->SecurityCode}', '{$xml->ISIN}', '{$xml->Quantity}', '{$xml->TotalCost}')";
                }else {
                    $papana .= " ('{$xml->ClientCode}', '{$xml->SecurityCode}', '{$xml->ISIN}', '{$xml->Quantity}', '{$xml->TotalCost}'),";
                }
                $i++;
            }

            /*for($i=0; $i<=5; $i++) {
                if($i==5) {
                    $papana .= " ('{$xml->ClientCode}', '{$xml->SecurityCode}', '{$xml->ISIN}', '{$xml->Quantity}', '{$xml->TotalCost}')";
                }else {
                    $papana .= " ('{$xml->ClientCode}', '{$xml->SecurityCode}', '{$xml->ISIN}', '{$xml->Quantity}', '{$xml->TotalCost}'),";
                }
            }*/
            $full_string = $query_string . $papana;
            // dd($full_string);
            DB::select("TRUNCATE batch_data");
            DB::select($full_string);

            /*foreach($xml_data as $xml) {

                if(!$xml->SecurityCode) {
                    return redirect()->route('upload_batch_data')
                    ->with('flash_msg', 'Invalid file format');
                }

                $batch = new BatchData;
                $batch->client_code = $xml->ClientCode;
                $batch->security_code = $xml->SecurityCode;
                $batch->isin = $xml->ISIN;
                $batch->quantity = $xml->Quantity;
                $batch->total_cost = $xml->TotalCost;
                $batch->save();
            }*/

            return redirect('/manage_bo_account')->with('flash_msg', 'Batch data uploaded successfully');
        }

        return view('admin.upload_batch_data', $data);
    }

    public function papana() {
        dd("Hello World");
    }

    public function all_user_withdrawal(Request $request) {
        $data = [];
        
        if($request->ajax()) {
            // dd("withdraw");
            $withdraw_id = $request->stock_id;
            $status = $request->order_status;

            $order = WithdrawRequest::find($withdraw_id);
            $order->status = $status;
            $order->save();
            return redirect()->back()->with('success','Status updated successfully, Thank you');
        }

       $action = $request->submit;
        if($action == "Submit") {
            $from = date("Y-m-d 00:00:00", strtotime($request->from_date));
            $to = date("Y-m-d 23:59:59", strtotime($request->to_date));

            Log::info("SELECT * FROM order_management WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");

        $get_data = DB::select("SELECT * FROM withdraw_request WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
        return view('admin.all_user_withdrawal', compact('get_data'));
        }
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        // $data['get_data'] = DB::select("SELECT * FROM withdraw_request WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");

        $get_data = [];
        return view('admin.all_user_withdrawal', compact('get_data'));
    }

    public function all_withdraw_req_data() {
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        $str = "";
        $get_data = DB::select("SELECT wr.*, u.name FROM withdraw_request AS wr INNER JOIN users AS u ON wr.user_id=u.id ORDER BY wr.id DESC");
        // dd($get_data);
        
        $str .= "<table class='table table-hover'>";
        $str .= "<tr><th>SL</th><th>Client Code</th><th>Name</th><th>Mobile</th><th>Bank Name</th><th>Branch Name</th><th>Account No</th><th>Amount</th><th>Request Date</th><th>Status</th><th>Action</th></tr>";
        $i=1;
        foreach($get_data as $val) {

            $str .= "<tr>";
                $str .= "<td>" . $i . "</td>";
                $str .= "<td>" . $val->client_code . "</td>";
                $str .= "<td>" . $val->name . "</td>";
                $str .= "<td>" . $val->mobile . "</td>";
                $str .= "<td>" . $val->bank_name . "</td>";
                $str .= "<td>" . $val->branch_name . "</td>";
                $str .= "<td>" . $val->account_no . "</td>";
                $str .= "<td>" . $val->amount . "</td>";
                $str .= "<td>" . date("jS F Y", strtotime($val->created_at)) . "</td>";
                $str .= "<td>";

                    if($val->status == 1) :
                    $str .= "<label class='label label-danger'>Pending</label>";
                    elseif($val->status == 2) :
                    $str .= "<label class='label label-primary'>Submitted</label>";
                    elseif($val->status == 3) :
                    $str .= "<label class='label label-warning'>Rejected</label>";
                    elseif($val->status == 4) :
                    $str .= "<label class='label label-warning'>Cancel</label>";
                    elseif($val->status == 5) :
                    $str .= "<label class='label label-success'>Executed</label>";
                    elseif($val->status == 6) :
                    $str .= "<label class='label label-danger'>Canceled By User</label>";
                    endif;
                $str .= "</td>";
                $str .= "<td>";
                    // if( ($val->order_status==1) || ($val->order_status==2) || ($val->order_status==3) || ($val->order_status==4) ) :
                        $str .= "<a class='change_stock_status btn btn-primary btn-xs' data-id='{$val->id}' data-status='{$val->status}' href=''>Change Status</a>";
                        $str .= "<a class='withdraw-form btn btn-warning btn-xs' data-id='{$val->id}' href=''>Print</a>";
                    // endif;
                $str .= "</td>";
            $str .= "</tr>";

            $i++;
        }
        $str .= "</table>";

        echo $str;

    }


    private function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

    public function view_withdraw_print($id) {

        // $kaka = $this->convertNumberToWord(101);

        $data = [];
        $get_data = WithdrawRequest::where('id', $id)->first();
        $num_in_word = $this->convertNumberToWord($get_data->amount);
        $client_code = $get_data->client_code;
        $ledger_balance = 0;
        $from_date = date("d M Y");
        $to_date = date("d M Y");

        $url = "http://123.0.17.7/api/sptest_7.php?from_date=".$from_date . '&client_code='. $client_code . '&to_date='.$to_date;
        $papana = file_get_contents($url);
        $get_data = json_decode($papana);
        if(count($get_data)) {
            $counting = count($get_data) - 1;
            $ledger_balance = $get_data[$counting];
            $ledger_balance = $ledger_balance->Balance;
        }
        $ledger_balance = $ledger_balance;

        return view('admin.view_withdraw_print', compact('get_data','ledger_balance'));
    }

    public function settings(Request $request) {

        $data = [];
        $action = Input::get('submit');
        $data['data'] = Settings::all();
        $data['data'] = $data['data'][0];
        // dd($action);
        if($action == "Save change") {
            // dd($request->all());
            // $this->validate($request, [
            //     'phone'   => 'required',
            //     'mobile' => 'required',
            //     'order_submission_from' => 'required',
            //     'order_submission_to' => 'required',
            //     'market_open_time' => 'required',
            //     'market_close_time' => 'required',
            //     // 'crone_start' => 'required',
            //     // 'crone_end' => 'required',
            // ]);
            $id = $data['data']->id;
            //dd($request->all());
            $settings = Settings::find($id);
            $settings->company_title = $request->company_headiing;
            $settings->phone = $request->phone;
            $settings->mobile = $request->mobile;
            $settings->email = $request->email;
            $settings->web = $request->web;
            $settings->order_submission_from = $request->order_submission_from;
            $settings->order_submission_to = $request->order_submission_to;
            $settings->market_open_time = $request->market_open_time;
            $settings->market_close_time = $request->market_close_time;
            $settings->crone_start = $request->crone_start;
            $settings->crone_end = $request->crone_end;
            $settings->address = $request->address;
            $settings->save;

            return redirect('settings')->with('flash_msg', 'Settings updated successfully');
        }

        return view('admin.settings', $data);

    }

    public function passwordPolicy(Request $request)
    {
        $pass_info = PasswordPolicy::orderBy('created_at','desc')->limit(1)->get();
        $data = [];
        $action = Input::get('submit');
        // $data['data'] = PasswordPolicy::all();
        // $data['data'] = $data['data'][0];

        if($action == "Save change") {
            $this->validate($request, [
                'policy_name'   => 'required',
                'valid_days' => 'required',
                'password_length' => 'required',
                // 'order_submission_to' => 'required',
                // 'market_open_time' => 'required',
                // 'market_close_time' => 'required',
                // 'crone_start' => 'required',
                // 'crone_end' => 'required',
            ]);
            
            $data = new PasswordPolicy;
            $data->policy_name = $request->policy_name;
            $data->valid_days = $request->valid_days;
            $data->password_length = $request->password_length;
            $data->special_character = $request->special_character;
            $data->digit = $request->digit;
            $data->uppercase = $request->uppercase;
            $data->lowercase = $request->lowercase;
            $data->session_time = $request->session_time;

            // if($data->special_character == null)
            //     $data->special_character = 0

            $data->save();
            return redirect('settings')->with('flash_msg', 'Password Policy Settings updated successfully');
        }

        return view('admin.password_policy',['pass_info'=>$pass_info]);
    }

    public function admin_change_password(Request $request) {

        $data = [];
        $pass_info = PasswordPolicy::orderBy('created_at','desc')->limit(1)->get();
        // dd($pass_info);exit();
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'new_password'   => 'required',
                'confirm_password' => 'required'
            ]);
            if(strlen($request->new_password) < $pass_info[0]->password_length) {
                return redirect()->back()->with('flash_msg', 'Password must be '.$pass_info[0]->password_length.' or more than '.$pass_info[0]->password_length. ' character');
            }elseif($pass_info[0]->digit != null && !preg_match("#[0-9]+#",$request->new_password)) {
                return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Number!");
            }elseif($pass_info[0]->uppercase != null && !preg_match("#[A-Z]+#",$request->new_password)) {
                return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Capital Letter!");
            }elseif($pass_info[0]->lowercase != null && !preg_match("#[a-z]+#",$request->new_password)) {
                return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Lowercase Letter!");
            }elseif($pass_info[0]->special_character != null && !preg_match("#\W+#",$request->new_password)) {
                return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Special Character!");
            }

            if($request->new_password !== $request->confirm_password) {
                return redirect()->back()->with('flash_msg', 'New password & Confirm Password didn\'t match');
            }

            /*$hash1 = bcrypt(123456);
            $hash2 = bcrypt($request->new_password);

            $papa = Hash::check($request->new_password, $hash1);
            $kaka = Hash::check($request->new_password, $hash2);

            $lukakau = Hash::make(bcrypt($request->new_password));

            dd($hash1);*/

            $change_pass = User::find(Auth::user()->id);
            $change_pass->password = bcrypt($request->new_password);
            $change_pass->save();


            return redirect('admin_change_password')->with('flash_msg', 'Password changed successfully');
        }

        return view('admin.admin_change_password', $data);

    }

    public function admin_profile(Request $request) {
        $data = [];
        $data['get_data'] = User::where('id', Auth::user()->id)->first();
        $action = Input::get('submit');
        if($action == "Update Profile") {

            $upload = $request->file('upload_file');
            $upload2 = $request->file('upload_file2');
            $filename = "";
            $filename2 = "";
            if($upload) {
                $filename = $_FILES['upload_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('admin_profile')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/user/". $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination);
            }
            if($upload2) {
                // dd($upload2);
                $filename2 = $_FILES['upload_file2']['name'];
                $ext = pathinfo($filename2, PATHINFO_EXTENSION);
                $accept_files = ["jpeg", "jpg", "png", "bmp", "gif"];
                if(!in_array($ext, $accept_files)) {
                    return redirect()->route('admin_profile')
                    ->with('flash_msg', 'Invalid file extension. permitted file is .jpg, .jpeg & .png');
                }
                // get the file
                $upload = $request->file('upload_file2');
                $filePath = $upload->getRealPath();
                $destination = public_path() ."/custom_files/signature/". $filename2;
                move_uploaded_file($_FILES['upload_file2']['tmp_name'], $destination);
            }

            $admin_profile = User::find(Auth::user()->id);
            // dd($admin_profile);
            $admin_profile->name = $request->name;
            // $admin_profile->client_code = $request->client_code;
            $admin_profile->mobile = $request->mobile;
            $admin_profile->image = ($upload) ? $filename : $data['get_data']->image;
            $admin_profile->signature = ($upload2) ? $filename2 : $data['get_data']->signature;
            $admin_profile->save();

            return redirect('admin_profile')->with('flash_msg', 'Profile updated successfully');
        }
        return view('admin.admin_profile', $data);
    }

    public function subscribers_list() {
        $data = [];
        $data['get_data'] = Subscribers::all();
        return view('admin.subscribers_list', $data);
    }

    public function download_all_subscribers() {
        $data = Subscribers::all();
        $all_data = [];
        foreach($data as $val) {
            $all_data[] = $val->email;
        }
        
        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="sample.csv"');

        $fp = fopen('php://output', 'w');
        foreach ( $all_data as $line ) {
            $val = explode(",", $line);
            fputcsv($fp, $val);
        }
        fclose($fp);

    }

    public function change_user_pass(Request $request) {
        $pass_info = PasswordPolicy::orderBy('created_at','desc')->limit(1)->get();
        $user_id = $request->user_id;
        $password = $request->password;

        if(strlen($password ) < $pass_info[0]->password_length) {
            return redirect()->back()->with('flash_msg', 'Password must be '.$pass_info[0]->password_length.' or more than '.$pass_info[0]->password_length. ' character');
        }elseif($pass_info[0]->digit != null && !preg_match("#[0-9]+#",$password )) {
            return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Number!");
        }elseif($pass_info[0]->uppercase != null && !preg_match("#[A-Z]+#",$password )) {
            return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Capital Letter!");
        }elseif($pass_info[0]->lowercase != null && !preg_match("#[a-z]+#",$password )) {
            return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Lowercase Letter!");
        }elseif($pass_info[0]->special_character != null && !preg_match("#\W+#",$password )) {
            return redirect()->back()->with('flash_msg',"Your Password Must Contain At Least 1 Special Character!");
        }

        $change_pass = User::find($user_id);
        $change_pass->password = bcrypt($password);
        $change_pass->save();
        return redirect('manage_user_account')->with('flash_msg', 'Password changed successfully. Your new password is: '.$password);
    }

    public function upload_dummy_data(Request $request) {
        if($request->submit) {
            $this->validate($request, [
                'upload_file'   => 'required'
            ]);

            $upload = $request->file('upload_file');
            $filename = $_FILES['upload_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $accept_files = ["csv", "txt", "xlsx"];
            if(!in_array($ext, $accept_files)) {
                return redirect()->route('upload_dummy_data')
                ->with('flash_msg', 'Invalid file extension. permitted file is .csv, .txt & .xlsx');
            }

            // get the file
            $upload = $request->file('upload_file');
            $filePath = $upload->getRealPath();

            if($ext == "xlsx" || $ext == "xls") {
                $result = Excel::load($filePath, function($reader) {
                    $reader->all();
                })->get();

                // dd($result);

                foreach($result as $key => $val) {
                    $ind = new IndustryData;
                    $ind->COMPANY_CODE = $val->company_code;
                    $ind->COMPANY_NAME = $val->company_name;
                    $ind->CATEGORY = $val->category;
                    $ind->CREATED_BY = Auth::user()->name;
                    $ind->save();
                }
                return redirect('upload_dummy_data')->with('flash_msg', 'data db inserted successfully');
            }


        }
        return view('admin.upload_dummy_data');
    }

    public function update_cash_limit(Request $request) {
        $data = [];
        $action =$request->submit;
        if($action == "Submit") {
            $security_code = $request->security_code;
            $amount = (int)$request->amount;
            $client_data = ClientLimits::where('clientcode', $security_code)->first();
            if($security_code ==$client_data) {
                return redirect()->back()->with('failed', 'Wrong client code');
            }
            if($amount < 1) {
             return redirect()->back()->with('failed', 'Correct your amount please');
            }
            $prev_cash = $client_data->cash;
            $updated_cash = $prev_cash + $amount;

            DB::select("UPDATE client_limits SET cash={$updated_cash} WHERE clientcode='{$security_code}' LIMIT 1");
            return redirect()->back()->with('success', "Cash limit {$amount} updated for security code {$security_code}, current limit now {$updated_cash}");
            //dd($updated_cash);
        }
        return view('admin.update_cash_limit');
    }

    public function download_database() {

        $url = "http://123.0.17.7/backup/backup.php";

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data);

        return redirect('home')->with('flash_msg', 'Process completed successfully');

    }
    /********************* Send new Email for create Password *************/
    public function sendNewEmail($id)
    {
        $user = User::where('id',$id)->first();
        $user->email_token = sha1(time());
        $user->save();

        // dd($user);

        // var_dump($user);
        \Mail::to($user->email)->send(new EmailVerification($user));
        return redirect('/manage_user_account')->with('status','Send User new Verify email.');
    }
   /**********************************************************************/

   /********************* deposit *************/ 
       public function all_user_deposit(Request $request) {
        $data = [];
        
        if($request->ajax()) {
            // dd("hi");
            $withdraw_id = $request->stock_id;
            $status = $request->order_status;
            // dd($withdraw_id);
            $order = Deposit::find($withdraw_id);
            $order->status = $status;
            $order->save();
            return redirect()->back()->with('success','Status updated successfully, Thank you');
        }

       $action = $request->submit;
        if($action == "Submit") {
            $from = date("Y-m-d 00:00:00", strtotime($request->from_date));
            $to = date("Y-m-d 23:59:59", strtotime($request->to_date));

            // Log::info("SELECT * FROM order_management WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");

        $get_data = DB::select("SELECT * FROM deposit WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
        return view('admin.all_user_deposit', compact('get_data'));
        }
        $from = date("Y-m-d");
        $from = $from . " 00:00:00";
        $to = date("Y-m-d");
        $to = $to . " 23:59:59";
        $get_data = DB::select("SELECT * FROM deposit WHERE created_at BETWEEN '{$from}' AND '{$to}' ORDER BY id DESC");
        //$data['get_data'] = WithdrawRequest::all();
        return view('admin.all_user_deposit', compact('get_data'));
    }
  /*********************(Arif khan)*****************************************/
  /*********************Upload Industry Data********************************/
    public function upload_industry_data(Request $request) {
        $data = [];
        $action = Input::get('submit');

        if($action == "Save change") {
            $this->validate($request, [
                'upload_file'   => 'required'
            ]);

            // checkings
            $filename = $_FILES['upload_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            // $ext = File::extension($request->file->getClientOriginalName());
            $accept_files = ["xlsx", "XLSX","XLS","xls"];
            if(!in_array($ext, $accept_files)) {
                return redirect()->route('upload_industry_data')
                ->with('flash_msg', 'Invalid file extension. permitted file is .xml');
            }
            
            $path_xlsx = Input::file('upload_file')->getRealPath();
            //$xlsx_data = Excel::load($_FILES['upload_file']['tmp_name']);
            $xlsx_data = Excel::load($path_xlsx,function($reader) {})->get();

            //echo $xlsx_data[0];exit();
            //$xlsx_data = json_encode($xlsx_data);
            if(!empty($xlsx_data)){
                //print_r($xlsx_data);exit();
                 foreach ($xlsx_data as  $key => $value) {
                    // echo $value->industry_name."<pre/>";
                  $copy_data = DB::table('INDUSTRY_DATA')->where('COMPANY_CODE',$value->company_code)->get();
                  
                  if(empty($copy_da)){
                  $insert[] = [
                  'ID' => $value->id,
                  'INDUSTRY_NAME' => $value->industry_name,
                  'COMPANY_CODE' => $value->company_code,
                  'COMPANY_NAME' => $value->company_name,
                  'CATEGORY' => $value->category,
                  'CREATED_BY' => $value->created_by,
                  'CREATED_AT' => $value->created_at,
                  'UPDATED_BY' => $value->updated_by,
                  'UPDATED_AT' => $value->updated_at,
                  'STATUS' => $value->status,
                  ];
                 }else {
                  $update[] = [
                  'ID' => $value->id,
                  'INDUSTRY_NAME' => $value->industry_name,
                  'COMPANY_CODE' => $value->company_code,
                  'COMPANY_NAME' => $value->company_name,
                  'CATEGORY' => $value->category,
                  'CREATED_BY' => $value->created_by,
                  'CREATED_AT' => $value->created_at,
                  'UPDATED_BY' => $value->updated_by,
                  'UPDATED_AT' => $value->updated_at,
                  'STATUS' => $value->status,
                  ];
                 }
             }
                 if(!empty($update)) 
                    $updatedData = DB::table('INDUSTRY_DATA')->update($update);

                 if(!empty($insert)){
                     $insertData = DB::table('INDUSTRY_DATA')->insert($insert);
                  if ($insertData) {
                    return redirect('upload_industry_data')->with('flash_msg', 'Industry data uploaded successfully');
                  }else {                        
                    return redirect('upload_industry_data')->with('flash_msg', 'Error! Industry data uploaded Unsuccessful.');
                  }
                 }
             }
            return redirect('upload_industry_data')->with('flash_msg', 'No Industry data are found.');
        }

        return view('admin.upload_industry_data', $data);
    }
  /*********************(Arif khan)*****************************************/

  public function IPO()
  {
    $data = [];
    $data['get_data'] = IPOSetting::orderBy('id', 'desc')->get();
    return view('web.ipo_admin',$data);
  }

  public function ipo_setting()
  {
    return view('web.ipo_form');
  }

  public function saveIpoData(Request $request)
  {
    $data = $request->all();
    $insert = IPOSetting::create($data);
    if(!$insert) {
        return redirect('ipo_setting')->with('flash_msg', 'OOPs! something is wrong, try again.');
    }

    return redirect('/IPO')->with('flash_msg', 'IPO new data Store successfully.');
  }

  public function ipoStatusChange(Request $request, $id)
  {
     $data = IPOSetting::select('id','status')->where('id',$id)->first();
     $data->status = $data->status? 0:1;
     $updated_data = \DB::table('ipo_setting')
        ->where('id',$id)
        ->update(['status' => $data->status]);

     if($updated_data){
        return redirect('/IPO')->with('flash_msg', 'Status change successfully.');
     }
     return redirect('/IPO')->with('flash_msg', 'Please try again,Status change unsuccessfull.');
  }

  public function ipo_application(Request $request)
  {
    // $get_data = IPOApplication::get();
    $input_data = $request->all();
    // dd($input_data);
    $get_data = null;
    $data = IPOSetting::where('status',1)->get();

    if($input_data) {
       $get_data = IPOApplication::select('*')->where('ipo_setting_id',$input_data['script_id'])->get();
    }

    return view('web.ipo_application',['data'=>$data,'get_data'=>$get_data]);
  }

  public function save_change_ipo_status(Request $request)
  {

    $data = IPOSetting::where('status',1)->get();
    $input_data = $request->all();
       $updated_data = \DB::table('ipo_application')
            ->where('id',$input_data['ipo_application_id'])
            ->update(['status' => $input_data['ipo_request_status']]);
    $get_data = IPOApplication::get();
        if($updated_data) {
            return view('web/ipo_application',['data'=>$data,'get_data'=>$get_data])->with('flash_msg', 'IPO Request Status Update successfully.');
        }
        return view('web.ipo_application',['data'=>$data,'get_data'=>$get_data])->with('flash_msg', 'Please try again,IPO Request Status change unsuccessfull.');
  }
}
