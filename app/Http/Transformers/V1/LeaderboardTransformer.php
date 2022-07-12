<?php

namespace App\Http\Transformers\V1;

use App\Http\Transformers\ResponseTransformer;
use Illuminate\Support\Facades\Storage;
use Google\Service\AIPlatformNotebooks\Status;

class LeaderboardTransformer
{
    public function list($code, $message, $model)
    {
        $return = [];
        foreach ($model as $data) {
            $tmp = $this->item($data);
            $return[] = $tmp;
        }

        return (new ResponseTransformer)->toJson($code, $message, $model, $return);
    }

    public function item($model)
    {
        $tmp = new \stdClass;
        $tmp->user_id = $model->id;
        $tmp->first_name = $model->first_name;
        $tmp->last_name = $model->last_name;
        $tmp->creator_total_point = $model->point_sum_value;
        $tmp->image_profile = defaultImage('user', $model);
        $tmp->point = $this->_point($model->Point);


        return $tmp;
    }

    private function _point($model)
    {
        $tmp = new \stdClass;

        foreach ($model as $pt) {
            // Point data
            unset($pt->classroom_id);
            unset($pt->mission_id);
            unset($pt->respone_id);
            unset($pt->user_id_from);
            unset($pt->user_id_to);
            unset($pt->mission_comment_id);
            unset($pt->respone_comment_id);
            unset($pt->type);
            unset($pt->status);
            unset($pt->read_at);
            unset($pt->created_at);
            unset($pt->updated_at);
            unset($pt->deleted_at);

            // Response data
            unset($pt->Respone->user_id);
            unset($pt->Respone->mission_id);
            unset($pt->Respone->title);
            unset($pt->Respone->text);
            unset($pt->Respone->default_content_id);
            unset($pt->Respone->status);
            unset($pt->Respone->type);
            unset($pt->Respone->created_at);
            unset($pt->Respone->updated_at);
            unset($pt->Respone->deleted_at);
            $pt->Respone->image_full_path = getPublicFile($pt->Respone->image_path, $pt->Respone->image_file);
            $pt->Respone->content = $this->_responseContent($pt->Respone->MissionContentDefault);
            $pt->Respone->total_like = $pt->Respone->Like == null ? 0 : $pt->Respone->Like->count();

            // Mission data
            unset($pt->Mission->user_id);
            unset($pt->Mission->title);
            unset($pt->Mission->text);
            unset($pt->Mission->difficulty_level);
            unset($pt->Mission->timer);
            unset($pt->Mission->default_content_id);
            unset($pt->Mission->image_path);
            unset($pt->Mission->image_file);
            unset($pt->Mission->status);
            unset($pt->Mission->type);
            unset($pt->Mission->created_at);
            unset($pt->Mission->updated_at);
            unset($pt->Mission->deleted_at);

            $pt->Mission->my_response = null;

            unset($pt->Mission);
            unset($pt->Respone->MissionContentDefault);
            unset($pt->Respone->Like);
            unset($pt->Respone);
        }


        $tmp = $model->first();

        return $tmp;
    }

    private function _responseContent($model)
    {
        $tmp = new \stdClass;

        unset($model->mission_response_id);
        unset($model->title);
        unset($model->text);
        unset($model->file_mime);
        unset($model->type);
        unset($model->created_at);
        unset($model->updated_at);
        unset($model->deleted_at);


        $tmp = $model;
        $tmp->file_full_path = Storage::disk('gcs')->url($model->file_path . '/' . $model->file_name);


        return $tmp;
    }
}
