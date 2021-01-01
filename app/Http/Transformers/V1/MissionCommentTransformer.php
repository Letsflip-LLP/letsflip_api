<?php

namespace App\Http\Transformers\V1;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 

class MissionCommentTransformer {

    public function detail($code,$message,$model){
        $custome_model = $this->item($model);

        return (new ResponseTransformer)->toJson($code,$message,$model,$custome_model);
    }

    public function list($code,$message,$models){
        $custome_model = []; 
        foreach($models as $model ){
            $custome_model[] = $this->item($model);
        }

        return (new ResponseTransformer)->toJson($code,$message,$models,$custome_model);
    }

    public function item($model){

        $temp = new \stdClass(); 
        $temp->id           = $model->id; 
        $temp->parent_id    = $model->parent_id; 
        $temp->text         = $model->text; 
        $temp->user         = $this->_user($model->User);
        $temp->liked        = false;
        
        if(auth('api')->user() !=null && $model->Like)
            $temp->liked = $model->Like->where('user_id',auth('api')->user()->id)->count() > 0 ? true : false;
        
        $temp->comments      = [];

        foreach($model->Comment as $comm){
            $temp->comments[] = $this->item($comm);
        }

        $temp->total_like   = $model->Like->count(); 
        $temp->created_at   = dateFormat($model->created_at);

        return $temp;
    } 

    private function _childItem($model){
        return $this->item($model);
    }


    private function _user($model){
        $tmp = new \stdClass;
        $tmp->id            = $model->id;
        $tmp->first_name    = $model->first_name;
        $tmp->last_name     = $model->last_name;
        $tmp->image_profile = defaultImage('user');

        return  $tmp;
    }
 
}
