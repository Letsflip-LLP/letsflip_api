<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;

class QuickScoreTransformer {

    public function detail($code,$message,$model){ 
        $data = $this->item($model);

        return (new ResponseTransformer)->toJson($code,$message,$model,$data);
    }

    public function list($code,$message,$models){
       $datas = [];// $this->item($model);
        foreach($models as $model){
            $datas[] =  $this->item($model); 
        }

        return (new ResponseTransformer)->toJson($code,$message,$model,$datas);
    }

    public function item($model){
        $tmp        = new \stdClass();
        $tmp->id    = $model->id;
        $tmp->title = $model->title;
        // $tmp->option = [];

        for($i = 1 ; $i <=7 ; $i++){
            if($model['option'.$i])
                $tmp->option[] = (object) [
                    "id" => "option".$i,
                    "title" => $model['option'.$i]
                ];
        }

        return $tmp;
    }
}
