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
        $validatedData['name'] = $request->name;
        $validatedData['email'] = $request->email;

        $user = User::create($validatedData);

         $accessToken = $user->createToken('authToken')->accessToken;

         return (new ResponseTransformer)->toJson(200,"Success",['user' => $user, 'access_token' => $accessToken]); 
    }
}