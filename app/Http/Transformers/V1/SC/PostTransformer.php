<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;
 
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
            'user' => [
                'id' => $model->user->id,
                'first_name' => $model->user->first_name,
                'last_name' => $model->user->last_name,
                'email' => $model->user->email,
                'username' => $model->user->username,
                'image_profile' => defaultImage('user', $model->user)
            ],
            'contents' =>  $model->Content ? $this->contentRender($model->Content) : []
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
