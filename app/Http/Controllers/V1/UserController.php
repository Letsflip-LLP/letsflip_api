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
use App\Http\Models\UserFollowModel;
use Ramsey\Uuid\Uuid;

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
            $notif = $notif->orderBy('created_at','DESC');
            $notif = $notif->paginate($request->input('per_page',10));
      
        DB::commit();

            return (new NotificationTransformer)->list(200,"Success",$notif);

        } catch (\exception $exception){ 
            DB::rollBack(); 
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  

    }


    public function userFollowAction(Request $request)
    {        
        DB::beginTransaction();
        try { 
            $user = auth('api')->user(); 

            $action = 'add';

            $check = UserFollowModel::where([
                "user_id_from" => $user->id,
                "user_id_to"   => $request->user_id
            ])->first();

            if($check != null){
                $action = 'delete';
                $check->destroy($check->id);
            }else{
                $follow = UserFollowModel::insert([
                    "id" => Uuid::uuid4(),
                    "user_id_from" => $user->id,
                    "user_id_to"   => $request->user_id,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);
            };

            DB::commit(); 
 
            return (new ResponseTransformer)->toJson(200,__('messages.200'),$action);


        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
}