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
            $i_rep = [
                'id' => $rep->id,
                'text' => $rep->text,
                'total_share' => $rep->total_share,
                'total_like' => $rep->total_like,
                'total_comment' => $rep->total_comment,
            ];
            if ($rep->deleted_at !== NULL) {
                $i_rep['text'] = 'This Comment has been deleted';
                $i_rep['total_share'] = '';
                $i_rep['total_like'] = '';
                $i_rep['total_comment'] = '';
            }
            $temp['replies'][] = $i_rep;
        }

        return (object)$temp;
    }
}
