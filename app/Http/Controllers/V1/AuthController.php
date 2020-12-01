<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\AuthTransformer; 
use Illuminate\Auth\Events\Registered;
use DB;
use Illuminate\Support\Facades\Crypt;

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
}