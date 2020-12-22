<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\AuthTransformer; 
use DB;

class UserController extends Controller
{ 
    public function self(Request $request)
    { 
        $user = auth('api')->user(); 
        return (new AuthTransformer)->detail(200,"Success",$user); 
    }

    public function getPublicList(Request $request)
    { 
        $users = new User;

        if($request->filled('search')){
            $users = $users->where('first_name','LIKE',"%".$request->search."%");
            $users = $users->orWhere('last_name','LIKE',"%".$request->search."%");
            $users = $users->orWhere('email','LIKE',"%".$request->search."%");

        }
 
        $users = $users->get();

        return (new AuthTransformer)->list(200,"Success",$users);
    }
}