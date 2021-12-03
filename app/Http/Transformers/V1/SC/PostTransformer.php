<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;
use App\Http\Transformers\V1\UserTransformer; 
use App\Http\Transformers\V1\SC\EventTransformer; 

class PostTransformer
{

    public function detail($code, $message, $model)
    {
        $data = $this->item($model, 'detail');

        return (new ResponseTransformer)->toJson($code, $message, $model, $data);
    }

    public function list($code, $message, $models)
    {
        $data = [];
        foreach ($models as $model) {
            $data[] = $this->item($model, 'list');
        }

        return (new ResponseTransformer)->toJson($code, $message, $models, $data);
    }

    public function item($model, $type = null)
    {
        // dd($model->user);
        $temp = (object)[
            'id' => $model->id,
            'text' => $model->text,
            'total_share' => 0,
            'total_like' => $model->Like->count(),
            'total_comment' => $model->total_comment,
            'created_at' => dbLocalTime($model->created_at),
            'user' => (new UserTransformer)->item($model->user),
            'contents' =>  $model->Content ? $this->contentRender($model->Content) : [],
            'event' => $model->Event ? (new EventTransformer)->item($model->Event) : null
        ];

        return $temp;
    }

    public function contentRender($content){
        $data = [];
        foreach($content as $dat){
            $temp = $dat;
            $temp->file_full_path = getPublicFile($dat->file_path,$dat->file_name);
            $data[] = $temp;
        }

        return $data;
    }
}
