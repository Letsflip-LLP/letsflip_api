<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;
use App\Http\Models\SubscriberModel; 

class UserTransformer {  

    public function detail($code,$message,$model){
        $temp = new \stdClass();
        $temp->id         = $model->id;
        $temp->first_name = $model->first_name;
        $temp->last_name  = $model->last_name;
        $temp->email      = $model->email;
        $temp->username      = $model->username; 
        $temp->description  = $model->description;
        $temp->company      = null;
        $temp->followed      = false;

        $temp->blocked_user = $model->BlockedFrom->count();

        $temp->total_follower   = $model->Follower ? $model->Follower->count() : 0;
        $temp->total_following  = $model->Followed ? $model->Followed->count() : 0;
        $temp->total_classroom   = $model->ClassRoom ? $model->ClassRoom->count() : 0;

        if($model->Company && $model->Company->id && $model->Company->title){
            $temp->company  = [
                "id"    =>  $model->Company->id,
                "title" =>  $model->Company->title
            ];
        }

        if(auth('api')->user() !=null && $model->Followed)
            $temp->followed = $model->Follower->where('user_id_from',auth('api')->user()->id)->count() > 0 ? true : false;
 
        $temp->type = subsType(1);
        $temp->type->is_creator = true;

        $environment = request()->header('environment','production');
        $temp->environment = $environment;

        $sub = $model->Subscribe ? $model->Subscribe->where('is_creator',1)->where('user_id',$model->id)->orderBy('type','DESC')->first() : null;
        
        if($sub && $sub->type > 1){
            $temp->type = subsType($sub->type); 
            $temp->type->is_creator = $sub->is_creator ? true : false;
        }

        $temp->point      = $model->Point->sum('value');
        $temp->image_profile = defaultImage('user',$model);
        $temp->image_background = $model->image_background_path && $model->image_background_file ? (object) [
            "image_background_path" => $model->image_background_path,
            "image_background_file" => $model->image_background_file,
            "image_background_url" => getPublicFile($model->image_background_path,$model->image_background_file)
        ]:null;

        $temp->social_media = $this->sosmed($model->social_media_payload);

        $model->accessToken && $temp->access_token = $model->accessToken;

        return (new ResponseTransformer)->toJson($code,$message,$model,$temp);
    }

    private function sosmed($array){ 
        try {

            $data = [];
            $static_data = config('database.static_data.social_media_availlable');
        
            if($array != null){
                $array = json_decode($array);

                foreach($array as $key => $value){
                    if(isset($static_data[$key])){
                        $tmp        = (object) $static_data[$key];
                        $tmp->url = $value;
                        $data[] = $tmp;
                    } 
                }
            }

            return $data;
            
        DB::commit(); 
            return  $data; 
        } catch (\exception $exception){ 
            return  [];
        }   
    }

    public static function item($model){
        $tmp = new \stdClass;
        $tmp->id            = $model->id;
        $tmp->first_name    = $model->first_name;
        $tmp->last_name     = $model->last_name;
        $tmp->followed      = false;
        $tmp->username      = $model->username; 
        $tmp->total_follower   = $model->Follower ? $model->Follower->count() : 0;
        $tmp->total_following  = $model->Followed ? $model->Followed->count() : 0;

        if(auth('api')->user() !=null && $model->Followed)
            $tmp->followed = $model->Follower->where('user_id_from',auth('api')->user()->id)->count() > 0 ? true : false;

        $tmp->image_profile = defaultImage('user',$model);

        return  $tmp;
    }  

    public function list($code,$message,$model){
        $return  = [];
        foreach($model as $data){
            $tmp = $this->item($data);
            $return[] = $tmp;
        }
 
        return (new ResponseTransformer)->toJson($code,$message,$model,$return);
    }
 
}
