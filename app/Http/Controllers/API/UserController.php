<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOtp;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPassword;
use App\Http\Requests\ResetPassword;
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

                $user_id = $user->id;
                $email_token = rand(111111,999999);
                $encrypt_user_id = Crypt::encryptString($user_id);
                $base_url = env('BASE_URL');
                $link = $base_url.'/activate-account/'.$encrypt_user_id.'/'.$email_token;
    
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

    public function activateAccount($encrypt_id, $email_token)
    {   
        try {
            $user_id = Crypt::decryptString($encrypt_id);

            // check if user already activated his account
            $active_account = User::where('id', $user_id)->where('active', 1)->first();

            if($active_account !== null) {
                $data = [
                    'active' => 1
                ];
                return $this->sendResponse($data, 'Account successfully activated. Please login.');
            }

            // check if email and token exist
            $email_token_exists = User::where('id', $user_id)->where('email_token', $email_token)->first();

            if($email_token_exists !== null) {
                // activate account
                $activate_account = User::where('id', $user_id)->update(['active' => 1, 'email_verified_at' => Carbon::now(), 'email_token' => null]);
                if($activate_account) {
                    $data = [
                        'active' => 1
                    ];
                    return $this->sendResponse($data, 'Account successfully activated. Please login.');
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

           $otp = rand(111111,999999);
           $base_url = env('BASE_URL');

           if($user != null) {
                $user_id = $user->id;
                $encrypt_user_id = Crypt::encryptString($user_id);
                $name = $user->name;
                $link = $base_url.'/api/reset_password/'.$encrypt_user_id.'/'.$otp;
                $now = Carbon::now();
                $nowPlus30Mins = Carbon::now()->addMinutes(30);

                $otp_data = [
                    'user_id' => $user_id,
                    'otp' => $otp,
                    'start_time' => $now,
                    'end_time' => $nowPlus30Mins,
                ];

                UserOtp::create($otp_data);

                // send forgot password mail
                $mailData = [
                    'name' => $name,
                    'link' => $link
                ];

                Mail::to($email)->send(new ForgotPasswordMail($mailData));
                return $this->sendResponse($data, 'A Reset Password email has been sent to you. Please follow the instruction in the email to reset your password.');
           } else {
                return $this->sendError($email .' does not exist in our records');
           }
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function reset_password($encrypt_id, $otp)
    {   
        try {

            $user_id = Crypt::decryptString($encrypt_id);
            $userOTP = UserOtp::where('user_id', $user_id)->where('otp', $otp)->first();

            if($userOTP == null) {
                return $this->sendError('Invalid link');
            } 

            $now = strtotime(\Carbon\Carbon::now());
            $end_time = strtotime(\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $userOTP->end_time));
            $minutesDifference = ceil(($end_time - $now) / 60);

            if (($minutesDifference <= 0)) {
                return $this->sendError('Sorry, this link has expired');
            }

            UserOtp::where('user_id', $user_id)->update(['is_verified' => 1]);
            $email = User::where('id', $user_id)->value('email');

            $data = [
                'email' => $email
            ];

            return $this->sendResponse($data, 'Valid link');

        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function resetPassword(ResetPassword $request)
    {   
        try {
            $email = $request->email;
            $password = $request->password;

            $email_exists = User::where('email', $email)->exists();

            if(!$email_exists) {
                return $this->sendError('Email does not exist');
            }

            $data = [
                'password' => bcrypt($password)
            ];
            $update_password = User::where('email', $email)->update($data);

            $user_email = [
                'email' => $email
            ];

            if($update_password) {
                return $this->sendResponse($user_email, 'Password successfully changed.');
            } else {
                return $this->sendError('Unable to change password. Please try again.');
            }

        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }
}