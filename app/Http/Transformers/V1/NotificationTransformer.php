<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\V1\UserTransformer; 

class NotificationTransformer {
 
    public function item($model){ 
        $temp       = new \stdClass();
        $temp->title = $model->first_name." has responded 3 missions in Classroom: Basketball Court";
        $temp->text  = "has responded 3 missions in Classroom: Basketball Court ,has responded 3 missions in Classroom: Basketball Courthas responded 3 missions in Classroom: Basketball Courthas responded 3 missions in Classroom: Basketball Courthas responded 3 missions in Classroom: Basketball Courthas responded 3 missions in Classroom: Basketball Courthas responded 3 missions in Classroom: Basketball Court";
        
        $temp->user = UserTransformer::item($model);  

        $temp->created_at   = dateFormat($model->created_at);

        return $temp;
    } 
 
    public function list($code,$message,$models){
        $custome_model = []; 
        foreach($models as $model ){
            $custome_model[] = $this->item($model);
        } 
        return (new ResponseTransformer)->toJson($code,$message,$models,$custome_model);
    }


    
 
}
