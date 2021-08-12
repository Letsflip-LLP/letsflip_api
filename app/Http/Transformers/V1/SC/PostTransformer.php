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
            'total_share' => $model->total_share,
            'total_like' => $model->total_like,
            'total_comment' => $model->total_comment,
            'created_at' => $model->created_at,
            'user' => [
                'id' => $model->user->id,
                'first_name' => $model->user->first_name,
                'last_name' => $model->user->last_name,
                'email' => $model->user->email,
                'username' => $model->user->username,
                'image_profile' => defaultImage('user', $model->user)
            ]
        ];

        return $temp;
    }
}
