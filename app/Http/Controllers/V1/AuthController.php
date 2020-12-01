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

        DB::commit();

            event(new Registered($user));
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
}