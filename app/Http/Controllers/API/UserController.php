<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\WelcomeMail;
   
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

            $email_token = rand(1111111111,9999999999);
            $data = [
                'email_token' => $email_token,
            ];

            User::where('email', $email)->update($data);

            // send welcome email
            $mailData = [
                'name' => $name,
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
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Invalid Email/Password', ['error'=>'Unauthorised']);
        } 
    }
}