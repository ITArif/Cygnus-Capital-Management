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
                ]);
                //dd($auth->role);
                if ($auth->role == 2) {
                    if ($auth->status == 1) {
                        //dd("status ok");
                        return redirect('/admin-dashboard');
                    }else{
                        return redirect('/')->with('error', 'Your are not active.');
                    }

                } elseif ($auth->role == 1) {
                    if ($auth->status == 1) {
                        return redirect('/customer-dashboard');
                    } else {
                        return redirect('/')->with('error', 'Your are not active.');
                    }
                } else {
                    return redirect('/');
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
