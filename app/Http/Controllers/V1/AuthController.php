<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData['password'] = Hash::make($request->password);
        $validatedData['first_name'] = $request->first_name;
        $validatedData['last_name'] = $request->last_name;
        $validatedData['email'] = $request->email;

        $user = User::create($validatedData);

         $accessToken = $user->createToken('authToken')->accessToken;

         return (new ResponseTransformer)->toJson(200,"Success",['user' => $user, 'access_token' => $accessToken]); 
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData))
            return (new ResponseTransformer)->toJson(400,__('validation.password'),false);  
        
        if(auth()->user()->email_verified_at == null)
            return (new ResponseTransformer)->toJson(400,__('passwords.email_verification'),false);  

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
 
        return (new ResponseTransformer)->toJson(200,"Success",['user' => auth()->user() , 'access_token' => $accessToken]);  
    }
}