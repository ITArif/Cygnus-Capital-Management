<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    private $errors= [];
    protected $redirectTo = '/admin-dashboard';

    public function loginForm(){
        return view('auth.login');
    }

    public function loginCheck(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $auth = User::where('email','=', $request->email)->first();
        //dd($auth);
        if ($auth) {
            if (Hash::check($request->password, $auth->password)) {
                session([
                    'id' =>$auth->id,
                    'email' =>$auth->email,
                    'mobile' =>$auth->mobile,
                    'name' =>$auth->name,
                    'role' =>$auth->role,
                    'image' =>$auth->image,
                    'status' =>$auth->status,
                    'client_code' =>$auth->client_code,
                ]);

                //dd($auth->role);
                if ($auth->password_create_status == 1) {
                    if ($auth->role == 2) {
                        //dd("status ok");
                        return redirect('/admin-dashboard');
                    }elseif($auth->role == 1){
                        return redirect('/client-dashboard');
                    }
                } else {
                    return redirect('/')->with('failed','Please create your password.');
                }
            } else {
                return redirect('/')
                ->withInput($request->only('email'))
                ->with('error', 'Password do not match.');
            }
        } else {
            return back()->with('error', 'No account for this email');
        }
    }

    protected function guard()
    {
        return Auth::guard('guest');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
