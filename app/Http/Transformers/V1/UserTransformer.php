<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;

class UserTransformer {  
    public static function item($model){
        $tmp = new \stdClass;
        $tmp->id            = $model->id;
        $tmp->first_name    = $model->first_name;
        $tmp->last_name     = $model->last_name;
        $tmp->followed      = false;

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
