<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\PasswordResetModel;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\AuthTransformer; 
use Illuminate\Auth\Events\Registered;
use DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Libraries\RedisSocket\RedisSocketManager;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {

            $validatedData['password'] = Hash::make($request->password);
            $validatedData['first_name'] = $request->first_name;
            $validatedData['last_name'] = $request->last_name;
            $validatedData['email'] = $request->email;

            $user = User::create($validatedData);

            $accessToken = $user->createToken('authToken')->accessToken;

            $user->accessToken = $accessToken;

            $validatedData['activate_url'] = env('WEB_PAGE_URL',url('/')).'/account/verification?temporary_token='.Crypt::encryptString($validatedData['email']);
            $validatedData['password'] = $request->password;
            $send_mail = \Mail::to($validatedData['email'])->send(new \App\Mail\verificationUserRegister($validatedData));

        DB::commit();
 
           return (new AuthTransformer)->detail(200,"Success",$user);

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        } 
    }

    public function login(Request $request)
    { 
        DB::beginTransaction();
        try {
            $loginData = [
                'email' => $request->email,
                'password' => $request->password
            ];
    
            if (!auth()->attempt($loginData))
                return (new ResponseTransformer)->toJson(400,__('validation.password'),false);  
            
            if(auth()->user()->email_verified_at == null)
                return (new ResponseTransformer)->toJson(400,__('passwords.email_verification'),false);  
    
            $user = auth()->user();
            $accessToken = $user->createToken('authToken')->accessToken;
            $user->accessToken = $accessToken;

            DB::commit();

            return (new AuthTransformer)->detail(200,__("messages.200"),$user); 

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function verificationAccount(Request $request)
    {        
        DB::beginTransaction();
        try {
        
            $message = "";
            
            $decode = Crypt::decryptString($request->temporary_token);

            $user   = User::where('email',$decode)->first();
            
            if($user == null)
                $message = __('messages.401');

            if($user->email_verified_at != null)
                $message = "Oops! Your account is already verified";

            if($user->email_verified_at == null){
                if($user->update(['email_verified_at' => date('Y-m-d H:i:s')]))
                    $message = "CONGRATULATIONS! Your account has successfully activated! The world is now your classroom";
            } 

            $data = [];
            $data['message'] = $message;

            DB::commit(); 

        } catch (\exception $exception){
            DB::rollBack();
            $data['message'] = $exception->getMessage(); 
        }  

        return view('accounts.verification',$data);
    }

    public function requestResetPassword(Request $request)
    {        
        DB::beginTransaction();
        try {
        
            $user = User::where('email',$request->email)->first();

            if($user == null)
                return (new ResponseTransformer)->toJson(400,__('messages.404'),false); 

            $first_token = Crypt::encryptString($user->email);

            // DELETE OLD REQUEST
            PasswordResetModel::where('email',$request->email)->delete();

            $pass_res = new PasswordResetModel;
            $pass_res->email = $user->email;
            $pass_res->token = $first_token; 
            if(!$pass_res->save())
                return (new ResponseTransformer)->toJson(400,__('messages.400'),false);

            $return = new \stdClass();
            $return->temporary_token    =  $first_token;
            
            $email_payload = [
                "full_name" => $user->first_name.' '.$user->last_name,
                "reset_password_url" => env('WEB_PAGE_URL',url('/')).'/account/confirm-reset-password?temporary_token='.Crypt::encryptString($first_token)
            ];

            $send_mail = \Mail::to($user->email)->send(new \App\Mail\resetPasswordConfirmation($email_payload));

            DB::commit(); 

            return (new ResponseTransformer)->toJson(200,__('messages.200'),$return);

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }   
    }


    public function confirmResetPassword(Request $request){
        DB::beginTransaction();
 
        try {
            $data['message'] = "";

            $first_token = Crypt::decryptString($request->temporary_token);
            $email       = Crypt::decryptString($first_token); 
            $pass_res = new PasswordResetModel;
            $pass_res = $pass_res->where('email',$email)->where('token',$first_token)->where('is_verification',false)->first();
            
            if($pass_res == null){
                $data['message']  = "Oops! This page has been expired.";
                return view('accounts.confirmation-ress-pass',$data); 
            }
 
            $update1 = $pass_res->where('email',$email)->where('token','!=',$first_token)->delete();
            $update2 = $pass_res->where('email',$email)->where('token',$first_token)->update([
                'is_verification' => true
            ]);

            DB::commit(); 

            if($update2){
                $data['message']  = "We have receive your request, kindly launch the app to reset your password.";  
                $RedisSocket = new RedisSocketManager;
                $RedisSocket = $RedisSocket->publishRedisSocket(1,"AUTH","UPDATE",["first_token" => $first_token ]);
            }
 
        } catch (\exception $exception){
            DB::rollBack();
            $data['message'] = $exception->getMessage(); 
        }   
        return view('accounts.confirmation-ress-pass',$data); 
    }

    public function submitResetPassword(Request $request){
        DB::beginTransaction();
 
        try {
            $email       = Crypt::decryptString($request->temporary_token);
            $pass_res = new PasswordResetModel;
            $pass_res = $pass_res->where('email',$email)->where('token',$request->temporary_token)->first();

            if($pass_res == null)
                return (new ResponseTransformer)->toJson(400,__('messages.404'),false);

            if($pass_res->is_verification == false)
                return (new ResponseTransformer)->toJson(400,__('passwords.reset_pass_verification_mail'),false);
            
            $user = User::where('email',$email)->first();

            $new_password = Hash::make($request->password);

            $update_pass = $user->update([
                "password" => $new_password
            ]);

            if(!$update_pass)
                return (new ResponseTransformer)->toJson(400,__('messages.400'),false);

            $pass_res->where('email',$email)->delete();
            
            DB::commit();
               
                return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
            DB::rollBack();
                return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }   
    }
}