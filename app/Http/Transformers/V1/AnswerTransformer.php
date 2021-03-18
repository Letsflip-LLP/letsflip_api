<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;

class AnswerTransformer {

    public function detail($code,$message,$model){ 
        $tmp        = new \stdClass();
        $tmp->list  = $this->item($model);
        $tmp->total_point  = array_sump(array_column('point',$tmp->list));

        return (new ResponseTransformer)->toJson($code,$message,$tmp,$model);
    }

    public function list($code,$message,$models){
       $datas = [];// $this->item($model);
        foreach($models as $model){
            $datas[] =  $this->item($model); 
        }

        $return = new \stdClass;;
        $return->list = $datas;
        $return->total_point  = array_sum(array_column($datas,'point'));

        return (new ResponseTransformer)->toJson($code,$message,$models,$return);
    }

    public function item($model){
        $tmp                = new \stdClass();
        $tmp->id            = $model->id; 
        $tmp->point         = $model->point;
        $tmp->answer        = [
                        "my_answer"     => $model->answer,
                        "true_answer"   => $model->Question['correct_option'],//$model->Question[$model->Question['correct_option']];
                        "title"   => $model->Question[$model->Question['correct_option']],
        ];

        $tmp->question   = [
            "title" => $model->Question->title
        ];

   

        return $tmp;
    }
}
