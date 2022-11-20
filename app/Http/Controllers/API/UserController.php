<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOtp;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPassword;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\WelcomeMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
  
class UserController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {   
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
        
            $name = $input['name'];
            $email = $input['email'];
    
            $user = User::create($input);
    
            if($user != null) {
    
                $email_token = rand(111111,999999);
                $encrypt_email = Crypt::encryptString($email);
                $base_url = env('BASE_URL');
                $link = $base_url.'/api/activate_account/'.$encrypt_email.'/'.$email_token;
    
                $data = [
                    'email_token' => $email_token,
                ];
    
                User::where('email', $email)->update($data);
    
                // send welcome email
                $mailData = [
                    'name' => $name,
                    'link' => $link
                ];
                 
                Mail::to($email)->send(new WelcomeMail($mailData));
    
                return $this->sendResponse($user, 'Registration successful.');
            } else {
                return $this->sendError('Registration failed. Please try again.', $data = []);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        try {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1])){ 
                $user = Auth::user(); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                $success['name'] =  $user->name;
       
                return $this->sendResponse($success, 'User login successfully.');
            } 
            else{ 
                return $this->sendError('Invalid Email/Password or Inactive account', ['error'=>'Unauthorised']);
            } 
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function activateAccount($encrypt_email, $email_token)
    {   
        try {
            $email = Crypt::decryptString($encrypt_email);

            // check if email and token exist
            $email_token_exists = User::where('email', $email)->where('email_token', $email_token)->first();

            if($email_token_exists !== null) {
                // activate account
                $activate_account = User::where('email', $email)->update(['active' => 1, 'email_verified_at' => Carbon::now(), 'email_token' => null]);
                if($activate_account) {
                    $data = [
                        'active' => 1
                    ];
                    return $this->sendResponse($data, 'Account successfully activated');
                } else {
                    return $this->sendError('Unable to activate account. Please try again.', $data = []);
                }
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function forgotPassword(ForgotPassword $request)
    {   
        try {
           $email = $request->email;
           // check if email exits
           $user = User::where('email', $email)->first();
           $data['email'] = $email;

           $email_token = rand(111111,999999);
           $encrypt_email = Crypt::encryptString($email);
           $base_url = env('BASE_URL');

           if($user != null) {
                $name = $user->name;
                $link = $base_url.'/api/reset_password/'.$encrypt_email.'/'.$email_token;
                $now = Carbon::now();
                $nowPlus15Mins = Carbon::now()->addMinutes(15);

                $otp_data = [
                    'email' => $email,
                    'otp' => $email_token,
                    'start_time' => $now,
                    'end_time' => $nowPlus15Mins,
                ];

                UserOtp::create($otp_data);

                // send forgot password mail
                $mailData = [
                    'name' => $name,
                    'link' => $link
                ];

                Mail::to($email)->send(new ForgotPasswordMail($mailData));
                return $this->sendResponse($data, 'Email exists');
           } else {
                return $this->sendError('Email does not exists');
           }
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }
}