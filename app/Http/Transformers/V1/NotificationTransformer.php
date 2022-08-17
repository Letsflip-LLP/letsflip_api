<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\V1\UserTransformer;
use App\Http\Transformers\V1\MissionTransformer;

class NotificationTransformer
{

    public static function item($model)
    {
        $temp           = new \stdClass();
        $temp->id       = $model->id;
        $temp->mission_id   = $model->mission_id;
        $temp->classroom_id = $model->classroom_id;
        $temp->module_detail  = null;

        $temp->type       = (object) [
            'id' => $model->type,
            'title' => __('notification.TYPE.' . $model->type)
        ];

        if ($model->ClassroomAccess)
            $temp->module_detail = (object) [
                "id"     => $model->ClassroomAccess->id,
                "status" => $model->ClassroomAccess->status,
                "status_name" => statusRequestName($model->ClassroomAccess->status)
            ];

        $temp->text     = "";
        $temp->title    = "";

        $temp->mission_detail = $model->Mission ? (object) [
            "id" => $model->mission_id
        ] : null;

        if ($model->type == 1 || $model->type == 2) {
            $text_ = $model->Mission ? $model->Mission->title : 'deleted mission';

            if ($model->type == 1)
                $temp->text    = __('notification.TEXT.' . $model->type, ['user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'module_title' => $text_]);


            if ($model->type == 2 && $model->ClassRoom && $model->ClassRoom->type == 1)
                $temp->text = __('notification.TEXT.2', ['type' => 'Public Classroom', 'user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'module_title' => $model->ClassRoom->title]);

            if ($model->type == 2 && $model->ClassRoom && $model->ClassRoom->type == 2)
                $temp->text = __('notification.TEXT.2B', ['type' => 'Private Classroom', 'user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'module_title' => $model->ClassRoom->title]);

            if ($model->type == 2 && $model->Mission && $model->Mission->ClassRoomTag) {
                $module_detail = $model->Mission->ClassRoomTag->where('id', $model->classroom_id);
                if ($module_detail && isset($module_detail[0])) {
                    $piv_status = $module_detail[0]->pivot->status;
                    $temp->module_detail = (object)[
                        "id" => $module_detail[0]->pivot->id,
                        "status" => $piv_status,
                        "status_name" => statusRequestName($piv_status)
                    ];
                }
            }
        }
        if ($model->type ==  3 || $model->type ==  4) {
            if ($model->type == 3)
                $text_ = $model->Mission ? $model->Mission->title : 'deleted mission';
            if ($model->type == 4)
                $text_ = $model->Respone && $model->Respone->Mission ? $model->Respone->Mission->title : 'deleted respone';

            $temp->text    = __('notification.TEXT.' . $model->type, ['user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'module_title' => $text_]);
        }
        if ($model->type ==  11 &&  $model->Point && $model->Point->type == 1)
            $temp->text    = __('notification.TEXT.11A', ['from' => "mission", "point" => emoth_point($model->Point->value), "module_title" => $model->Mission ? $model->Mission->title : ""]);

        if ($model->type ==  11 &&  $model->Point && $model->Point->type == 2)
            $temp->text    = __('notification.TEXT.11A', ['from' => "mission", "point" => emoth_point($model->Point->value), "module_title" => $model->Mission ? $model->Mission->title : ""]);

        if ($model->type ==  11 &&  $model->Point && $model->Point->type == 3)
            $temp->text    = __('notification.TEXT.11B', ['from' => "for your response", "point" => emoth_point($model->Point->value), "module_title" => $model->Mission ? $model->Mission->title : ""]);

        if ($model->type ==  11 &&  $model->Point && $model->Point->type == 4)
            $temp->text    = __('notification.TEXT.11B', ['from' => "from user response", "point" => emoth_point($model->Point->value), "module_title" => $model->Mission ? $model->Mission->title : ""]);

        if ($model->type ==  12 || $model->type ==  13)
            $temp->text  =   __('notification.TEXT.' . $model->type);

        if ($model->type == 14 || $model->type == 15 || $model->type == 16)
            $temp->text  =   __('notification.TEXT.' . $model->type, ['user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'module_title' =>  $model->ClassRoom ? $model->ClassRoom->title : '']);

        if ($model->type == 17 || $model->type == 18)
            $temp->text  =   __('notification.TEXT.' . $model->type, ['user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'module_title' => $model->Mission ? $model->Mission->title : 'Deleted mission']);

        if ($model->type == 19 && $model->ClassRoom)
            $temp->text  =   __('notification.TEXT.' . $model->type, ['type' => $model->ClassRoom && $model->ClassRoom->type == 1 ? "Public Class Room" : subsType($model->ClassRoom->type)->name, 'user_name' => $model->ClassRoom && $model->ClassRoom->User ? $model->ClassRoom->User->first_name . ' ' . $model->ClassRoom->User->last_name . "'s" : 'unname', 'module_title' => $model->ClassRoom ? $model->ClassRoom->title : '']);

        if ($model->type == 20)
            $temp->text  =   __('notification.TEXT.' . $model->type, ['classroom_title' => $model->ClassRoom ? $model->ClassRoom->title : '', 'mission_title' => $model->Mission ? $model->Mission->title : '']);

        if ($model->type == 21)
            $temp->text  =   __('notification.TEXT.' . $model->type, ['user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name, 'classroom_title' => $model->ClassRoom ? $model->ClassRoom->title : '', 'mission_title' => $model->Mission ? $model->Mission->title : '']);

        if ($model->type == 22)
            $temp->text  =  __('notification.TEXT.' . $model->type, ['point' => $model->Point->value]);

        if ($model->type == 23 && $model->ClassRoom && $model->ClassRoom->type == 2)
            $temp->text  =  __('notification.TEXT.23', ['type' => 'Private Classroom']);

        if ($model->type == 23 && $model->ClassRoom && $model->ClassRoom->type == 3)
            $temp->text  =  __('notification.TEXT.23B', ['type' => 'Master Classroom']);

        if ($model->type == 24)
            $temp->text  =  __('notification.TEXT.' . $model->type, ['user_name_from' => $model->ClassRoom->User ? $model->ClassRoom->User->first_name . ' ' . $model->ClassRoom->User->last_name : ""]);

        if ($model->type == 25)
            $temp->text  =  __('notification.TEXT.' . $model->type, ['user_name_from' => $model->UserFrom->first_name . ' ' . $model->UserFrom->last_name]);

        if ($model->type == 26)
            $temp->text  =  __('notification.TEXT.' . $model->type, ['classroom_title' => $model->ClassRoom()->onlyTrashed()->value('title') ? '"'.$model->ClassRoom()->onlyTrashed()->value('title').'"':'']);

        $temp->title        =   __('notification.TYPE.' . $model->type);
        $temp->user         =   $model->UserFrom ? UserTransformer::item($model->UserFrom) : UserTransformer::item($model->UserTo);

        $temp->created_at   = dateFormat($model->created_at);
        $temp->read_at      = dateFormat(Carbon::parse($model->read_at));


        // if($model->type == 22) dd($temp);

        return $temp;
    }

    public function list($code, $message, $models)
    {
        $custome_model = [];
        foreach ($models as $model) {

            if ($model->read_at == null)
                $model->update(['read_at' => date('Y-m-d H:i:s')]);

            $tmp = $this->item($model);

            if ($tmp->text)
                $custome_model[] = $tmp;
        }

        return (new ResponseTransformer)->toJson($code, $message, $models, $custome_model);
    }
}
