<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 

class AuthTransformer {

    public function detail($code,$message,$model){
        $temp = new \stdClass();
        $temp->first_name = $model->first_name;
        $temp->last_name  = $model->last_name;
        $temp->email      = $model->email;

        $model->accessToken && $temp->accessToken = $model->accessToken;

        return (new ResponseTransformer)->toJson($code,$message,$model,$temp);
    }
 
}
