<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\AuthTransformer; 
use App\Http\Transformers\V1\UserTransformer; 
use App\Http\Transformers\V1\NotificationTransformer; 
use App\Http\Models\NotificationModel;
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
 
        $users = $users->paginate($request->input('per_page',10));

        return (new UserTransformer)->list(200,"Success",$users);
    }

    public function getSelfNotification(Request $request){

            DB::beginTransaction();

        try {

            $user = auth('api')->user(); 
        
            $notif =  NotificationModel::where('user_id_to',$user->id);
            $notif = $notif->orderBy('created_at','ASC');
            $notif = $notif->paginate($request->input('per_page',10));
      
        DB::commit();

            return (new NotificationTransformer)->list(200,"Success",$notif);

        } catch (\exception $exception){ 
            DB::rollBack(); 
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  

    }
}