<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;
use App\Http\Transformers\V1\SC\RoomChannelTransformer;

class RoomCategoryTransformer
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
            'text' => $model->text,
            'user' => [
                'id' => $model->user->id,
                'first_name' => $model->user->first_name,
                'last_name' => $model->user->last_name,
                'email' => $model->user->email,
                'username' => $model->user->username,
                'image_profile' => defaultImage('user', $model->user)
            ],
            'server' => [
                'id' => $model->server->id,
                'name' => $model->server->name,
                'text' => $model->server->text,
                'user' => [
                    'id' => $model->server->user->id,
                    'first_name' => $model->server->user->first_name,
                    'last_name' => $model->server->user->last_name,
                    'email' => $model->server->user->email,
                    'username' => $model->server->user->username,
                    'image_profile' => defaultImage('user', $model->server->user)
                ],
            ],
            "channels" => $this->renderChannelItem($model->channels)
        ];

        return $temp;
    }

    private function renderChannelItem($channels){
        $data = [];
        foreach($channels as $channel){
            $tmp  = (new RoomChannelTransformer)->item($channel);
            $data[] = $tmp;
        }
        return $data;
    }
}
