<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use App\Models\User;
use App\Models\Service;
use App\Models\Monthlybill;
use App\Models\Organization;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            //'organization'=> 'required',
            //'mobile' => 'required|max:11',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration with a random email token
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            //'mobile'=>$data['mobile'],
            //'organization'=>$data['organization'],
            'password' => bcrypt($data['password']),
            'email_token' => str_random(10),
        ]);

    }
    /**
    *  Over-ridden the register method from the "RegistersUsers" trait
    *  Remember to take care while upgrading laravel
    */

    public function register(Request $request)
    {
        // Laravel validation
        $validator = $this->validator($request->all());
        if ($validator->fails()) 
        {
            $this->throwValidationException($request, $validator);
        }
        // Using database transactions is useful here because stuff happening is actually a transaction
        // I don't know what I said in the last line! Weird!
        DB::beginTransaction();
        try
        {
            $user = $this->create($request->all());
            // After creating the user send an email with the random token generated in the create method above
            $email = new EmailVerification(new User(['email_token' => $user->email_token, 'name' => $user->name]));
            Mail::to($user->email)->send($email);
            DB::commit();
            return redirect('/')
            ->with('success', 'We sent you an activation code. Please check your email.');
            
        }
        catch(Exception $e)
        {
            DB::rollback(); 
            return back();
        }
    }

    public function verify($token)
    {
        // The verified method has been added to the user model and chained here
        // for better readability
        $user = User::where('email_token',$token)->first();
        User::where('email_token',$token)->firstOrFail()->verified();
        return redirect('/passCreate/'.$user->id)->with('success','Your email verification is completed.Please create your password.');
    }
}
