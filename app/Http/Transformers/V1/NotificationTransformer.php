<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\V1\UserTransformer; 
use App\Http\Transformers\V1\MissionTransformer; 

class NotificationTransformer {
 
    public static function item($model){  
        $temp           = new \stdClass();
        $temp->id       = $model->id;
        $temp->text     = "";
        $temp->title    = "";

        $temp->mission_detail = $model->Mission ? (object) [
            "id" => $model->mission_id
        ]:null;

        if($model->type == 1 || $model->type== 2){
            $text_ = $model->Mission ? $model->Mission->title : 'deleted mission';
            $temp->text    = __('notification.TEXT.'.$model->type,[ 'user_name_from' => $model->UserFrom->first_name.' '.$model->UserFrom->first_name , 'module_title' => $text_ ]);    
        }
             
        if($model->type ==  3 || $model->type ==  4){
            if($model->type==3)
                $text_ = $model->Mission ? $model->Mission->title : 'deleted mission';
            if($model->type==4)
                $text_ = $model->Respone ? $model->Respone->title : 'deleted respone';

            $temp->text    = __('notification.TEXT.'.$model->type,[ 'user_name_from' => $model->UserFrom->first_name.' '.$model->UserFrom->first_name , 'module_title' => $text_]);         
        }

        if($model->type ==  11 &&  $model->Point && $model->Point->type == 1)
            $temp->text    = __('notification.TEXT.'.$model->type,[ 'from' => "for your first Mission!" , "point" => $model->Point->value]); 

        if($model->type ==  11 &&  $model->Point && $model->Point->type == 2)
            $temp->text    = __('notification.TEXT.'.$model->type,[ 'from' => "for Created Mission!" , "point" => $model->Point->value]); 

        if($model->type ==  11 &&  $model->Point && $model->Point->type == 3)
            $temp->text    = __('notification.TEXT.'.$model->type,[ 'from' => "for Created Response!" , "point" => $model->Point->value]); 
             
        $temp->title        =   __('notification.TYPE.'.$model->type);
        $temp->user         =   $model->UserFrom ? UserTransformer::item($model->UserFrom):UserTransformer::item($model->UserTo);

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
