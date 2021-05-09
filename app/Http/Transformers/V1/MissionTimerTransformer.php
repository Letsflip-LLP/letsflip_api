<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;

class MissionTimerTransformer {

    public function detail($code,$message,$model){ 
        $tmp             = $this->item($model);

        return (new ResponseTransformer)->toJson($code,$message,$model,$tmp);
    }

    public function item($model){
        $tmp             = new \stdClass(); 
        $tmp->id         = $model->id;
        $tmp->user_id    = $model->user_id;
        $tmp->mission_id = $model->mission_id;
        $tmp->timer      = $model->timer; 
        $tmp->time_start    = dbLocalTime($model->time_start);
        $tmp->time_end      = dbLocalTime($model->time_end);
        $tmp->time_second   = dbLocalTime($model->time_end) < dbLocalTime(date('Y-m-d H:i:s')) ? 0 : secondDiff($model->time_end);
        
        return $tmp;

    }
 
}
