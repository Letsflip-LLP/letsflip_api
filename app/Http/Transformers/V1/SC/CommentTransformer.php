<?php

namespace App\Http\Transformers\V1\SC;

use App\Http\Transformers\ResponseTransformer;

class CommentTransformer
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
            'total_share' => $model->total_share,
            'total_like' => $model->total_like,
            'total_comment' => $model->total_comment,
            'user' => [
                'id' => $model->User->id,
                'first_name' => $model->User->first_name,
                'last_name' => $model->User->last_name,
                'email' => $model->User->email,
                'username' => $model->User->username,
                'image_profile' => defaultImage('user', $model->User)
            ],
            'replies' => []
        ];
        if ($model->deleted_at !== NULL) {
            $temp['text'] = 'This Comment has been deleted';
            $temp['total_share'] = '';
            $temp['total_like'] = '';
            $temp['total_comment'] = '';
            return $temp;
        }

        foreach ($model->replies as $rep) { 
            $temp['replies'][] = $this->item($rep);
        }

        return (object)$temp;
    }
}
