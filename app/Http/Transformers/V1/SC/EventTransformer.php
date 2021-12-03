<?php

namespace App\Http\Transformers\V1\SC;
  
class EventTransformer
{ 
    public function item($model)
    { 
        $temp = (object)[
            "id" => $model->id,
            "user_id" => $model->user_id,
            "title" => $model->title,
            "text" => $model->text,
            "location" => $model->location,
            "date" => dbLocalTime($model->date),
            "file_path" => $model->file_path,
            "file_name" => $model->file_name,
            "file_mime" => $model->file_mime,
            "created_at" => dbLocalTime($model->created_at),
            "updated_at" => dbLocalTime($model->updated_at),
            "deleted_at" => dbLocalTime($model->deleted_at),
        ];

        return $temp;
    } 
}
