<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\V1\UserTransformer; 

class NotificationTransformer {
 
    public static function item($model){ 
        $temp           = new \stdClass();
        $temp->id       = $model->id;
        $temp->text     = "";
        $temp->title    = "";

        if($model->type == 1 || $model->type== 2)
            $temp->title    = __('notification.TEXT.'.$model->type,[ 'user_name_from' => $model->UserFrom->first_name.' '.$model->UserFrom->first_name , 'module_title' => $model->Mission->title]);    
            
        if($model->type ==  3 || $model->type ==  4)
            $temp->title    = __('notification.TEXT.'.$model->type,[ 'user_name_from' => $model->UserFrom->first_name.' '.$model->UserFrom->first_name , 'module_title' => $model->type==3 ? $model->Mission->title : $model->Respone->title]);         

        if($model->type ==  11 &&  $model->Point)
            $temp->title    = __('notification.TEXT.'.$model->type,[ 'from' => "for your first Mission!" , "point" => $model->Point->value]); 
             
        $temp->text     =   $temp->title;
        $temp->user     =   $model->UserFrom ? UserTransformer::item($model->UserFrom):UserTransformer::item($model->UserTo);

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
