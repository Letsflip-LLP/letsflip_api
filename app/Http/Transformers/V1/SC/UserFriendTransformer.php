<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;

class UserFriendTransformer
{

    public function detail($code, $message, $model)
    {
        $data = $this->item($model, 'detail');

        return (new ResponseTransformer)->toJson($code, $message, $model, $data);
    }

    public function list($code, $message, $models, $user)
    {
        $data = [];
        foreach ($models as $model) {
            $data[] = $this->item($model, 'list', $user);
        }

        return (new ResponseTransformer)->toJson($code, $message, $models, $data);
    }

    public function invitation($code, $message, $models, $user)
    {
        $data = [];
        foreach ($models as $model) {
            $data[] = $this->item($model, 'invitation', $user);
        }

        return (new ResponseTransformer)->toJson($code, $message, $models, $data);
    }

    public function item($model, $type = null, $user = NULL)
    {
        switch ($type) {
            case 'invitation':
                $temp = (object)[
                    'id' => $model->id,
                    'user' => [
                        'id' => $model->user_id_to,
                        'first_name' => $model->UserTo->first_name,
                        'last_name' => $model->UserTo->last_name,
                        'email' => $model->UserTo->email,
                        'username' => $model->UserTo->username,
                        'image_profile' => defaultImage('user', $model->UserTo)
                    ]
                ];
                break;
            case 'list':
                $usr = $model->UserTo;
                $temp = (object)[
                    'id' => $usr->id,
                    'first_name' => $usr->first_name,
                    'last_name' => $usr->last_name,
                    'email' => $usr->email,
                    'username' => $usr->username,
                    'image_profile' => defaultImage('user', $usr)
                ];
                break;
        }

        return $temp;
    }
}
