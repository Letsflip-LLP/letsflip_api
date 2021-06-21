<?php

namespace App\Http\Transformers\V1;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer;
use App\Http\Transformers\V1\UserTransformer; 
use Carbon\Carbon; 
use App\Http\Models\MissionResponeModel;

class PriceTransformer {
 
    public function list($code,$message,$models){
        foreach($models as $model){
            $custome_model[] =  $this->item($model);  
        }
         
        return (new ResponseTransformer)->toJson($code,$message,$models,$custome_model);
    }

    public function item($model){
        $temp = new \stdClass();  
        $temp->id = $model->id;
        $temp->price_group_vendor = $model->price_group_vendor;
        $temp->title              = $model->title;
        $temp->description        = $model->description;
        $temp->sgd                = number_format($model->sgd/1000000,2,',','');
        $temp->usd                = number_format($model->usd/1000000,2,',','');
        return $temp;
    }
 
}
