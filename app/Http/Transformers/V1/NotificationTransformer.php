<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\V1\UserTransformer; 

class NotificationTransformer {
 
    public function item($model){ 
        $temp           = new \stdClass();
        $temp->id       = $model->id;
        $temp->title    = __('notification.TEXT.'.$model->type,[ 'user_name_from' => $model->UserFrom->first_name.' '.$model->UserFrom->first_name , 'module_title' => $model->Mission->title]);         
        $temp->text     =   $temp->title;
        $temp->user     = UserTransformer::item($model->UserFrom);

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
