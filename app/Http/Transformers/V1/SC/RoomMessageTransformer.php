<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;
use App\Http\Transformers\V1\UserTransformer;

class RoomMessageTransformer
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
        $temp = [
            'id' => $model->id,
            'text' => $model->text,
            'user' => [
                'id' => $model->user->id,
                'first_name' => $model->user->first_name,
                'last_name' => $model->user->last_name,
                'email' => $model->user->email,
                'username' => $model->user->username,
                'image_profile' => defaultImage('user', $model->user)
            ],
            'created_at' => $model->created_at,
            'replies' => []
        ];
        if ($model->deleted_at !== NULL) {
            $temp['text'] = 'This Comment has been deleted';
            return $temp;
        }

        foreach ($model->replies as $rep) {
            $temp['replies'][] = $this->item($rep);
        }
        return $temp;
    }
}
