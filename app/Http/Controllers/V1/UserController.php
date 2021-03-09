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
use App\Http\Libraries\StorageCdn\StorageManager;
use Illuminate\Support\Facades\App;

use DB;

class UserController extends Controller
{ 
    public function self(Request $request)
    { 
        $user = auth('api')->user(); 
        return (new UserTransformer)->detail(200,"Success",$user); 
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

    public function userSelfUpdateProfile(Request $request)
    {        
        DB::beginTransaction();
        try { 
            $user = auth('api')->user(); 
            
            if($request->description)
                $user->description = $request->description;


            if($request->image_profile){
                $image_profile_upload = new StorageManager;
                $image_profile_upload = $image_profile_upload->uploadFile("public/user/profile",$request->file('image_profile'));    
                $user->image_profile_path = $image_profile_upload->file_path;
                $user->image_profile_file = $image_profile_upload->file_name;
            }

            if($request->image_background){
                $image_background_upload = new StorageManager;
                $image_background_upload = $image_background_upload->uploadFile("public/user/profile",$request->file('image_background'));    
                $user->image_background_path = $image_background_upload->file_path;
                $user->image_background_file = $image_background_upload->file_name;
            }


            if($request->social_media){
                $sosmed_list = [];
                foreach($request->social_media as $key => $val){
                    if($key && $val)
                        $sosmed_list[$key] = $val;
                };

                $sosmed = json_encode($sosmed_list);
                $user->social_media_payload = $sosmed;
            }

            $user->save();

            DB::commit(); 
 
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);


        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getPublicDetailUser(Request $request)
    { 
        $users = new User;
        
        $users = $users->where('id',$request->user_id); 
        $users = $users->first();


        return (new UserTransformer)->detail(200,"Success",$users);
    }

    public function availlableSocialMedia(Request $request){
        $static_data = config('database.static_data.social_media_availlable');
        return (new ResponseTransformer)->toJson(200,__('messages.200'),$static_data);
    }
}