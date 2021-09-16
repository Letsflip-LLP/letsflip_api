<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;

class RoomChannelTransformer
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
            'category' => [
                'id' => $model->category->id,
                'name' => $model->category->name,
                'text' => $model->category->text,
            ],
            'server' => (object) [
                "id" =>  $model->category->server_id
            ],
            'member_type' => [],
            'member' => []
        ];
        foreach ($model->memberType->where('type','!=',1) as $memType) {
            $temp->member_type[] = [
                'id' => $memType->id,
                'name' => $memType->name,
                'type' => $memType->type
            ];
        }
        // foreach ($model->member as $mem) {
        //     $temp->member[] = [
        //         'id' => $mem->id,
        //         'user' => [
        //             'id' => $mem->user->id,
        //             'first_name' => $mem->user->first_name,
        //             'last_name' => $mem->user->last_name,
        //             'email' => $mem->user->email,
        //             'username' => $mem->user->username,
        //             'image_profile' => defaultImage('user', $mem->user)
        //         ],
        //     ];
        // }

        return $temp;
    }
}
