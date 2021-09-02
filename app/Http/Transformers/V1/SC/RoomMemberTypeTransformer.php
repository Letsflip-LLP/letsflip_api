<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;

class RoomMemberTypeTransformer
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
        $temp = (object)[
            'id' => $model->id,
            'name' => $model->name,
            'type' => $model->type,
            'creator' => [
                'id' => $model->user->id,
                'first_name' => $model->user->first_name,
                'last_name' => $model->user->last_name,
                'email' => $model->user->email,
                'username' => $model->user->username,
                'image_profile' => defaultImage('user', $model->user)
            ],
            'channel' => [
                'id' => $model->channel->id,
                'name' => $model->channel->name,
                'text' => $model->channel->text,
            ]
        ];

        return $temp;
    }
}
