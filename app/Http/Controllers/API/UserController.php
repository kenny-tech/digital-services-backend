<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\WelcomeMail;
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

    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Invalid Email/Password or Inactive account', ['error'=>'Unauthorised']);
        } 
    }

    public function activateAccount($encrypt_email, $email_token)
    {   
        $email = Crypt::decryptString($encrypt_email);

        // check if email and token exist
        $email_token_exists = User::where('email', $email)->where('email_token', $email_token)->first();

        if($email_token_exists !== null) {
            // activate account
            $activate_account = User::where('email', $email)->update(['active' => 1, 'email_verified_at' => Carbon::now()]);
            if($activate_account) {
                $data = [
                    'active' => 1
                ];
                return $this->sendResponse($data, 'Account successfully activated');
            } else {
                return $this->sendError('Unable to activate account. Please try again.', $data = []);
            }
        }
    }
}