<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\MissionModel;
use App\Http\Models\UserPointsModel;
use App\Http\Libraries\Notification\NotificationManager;
use App\Http\Models\MissionReportModel;
use App\Http\Models\MissionResponeModel;
use App\Http\Models\ClassRoomModel;
use App\Http\Models\MissionCommentModel;
use App\Http\Transformers\ResponseTransformer;
use Google\Service\Bigquery\Resource\Models;
use phpDocumentor\Reflection\Types\This;

use function PHPSTORM_META\type;

class AdminMissionController extends Controller
{
    public function takeDown($key)
    {
        $missionReport = new MissionReportModel;

        $missionReport = $missionReport->whereHas('Mission', function ($q) use ($key) {
            $q->where('mission_id', $key);
        })->orWhereHas('Response', function ($q) use ($key) {
            $q->where('mission_respone_id', $key);
        })->orWhereHas('Classroom', function ($q) use ($key) {
            $q->where('classroom_id', $key);
        })->orWhereHas('MissionComment', function ($q) use ($key) {
            $q->where('mission_comment_id', $key);
        });

        $missionReport = $missionReport->first();

        if ($missionReport->Mission) {
            $model = new MissionModel;
            $id = 'mission_id';
            $type = [1, 2];
            $point = UserPointsModel::where($id, $key)
                ->where('user_id_to', $missionReport->Mission->user_id)
                ->whereIn('type', $type)
                ->first();
        } elseif ($missionReport->Response) {
            $model = new MissionResponeModel;
            $id = 'respone_id';
            $type = [3];
            $point = UserPointsModel::where($id, $key)
                ->where('user_id_to', $missionReport->Response->user_id)
                ->whereIn('type', $type)
                ->first();
        } elseif ($missionReport->Classroom) {
            $model = new ClassRoomModel;
            $id = null;
            $type = null;
            $point = null;
        } elseif ($missionReport->MissionComment) {
            $model = new MissionCommentModel;
            $id = null;
            $type = null;
        } else {
            $model = null;
            $id = null;
            $type = null;
            $point = null;
        }

        $content = $model->where('id', $key)->first();

        if ($content == null)
            return (new ResponseTransformer)->toJson(400, __('message.404', "ERRDELMIS1"));

        if (!$content->delete())
            return (new ResponseTransformer)->toJson(400, __('message.404', "ERRDELMIS1"));

        // REMOVE POINT
        if ($point) {
            $point->delete();
        }

        $data = [
            "key" => $key,
            "user_id" => $content->user_id,
            "content_id" => $content->id,
            "id" => $id,
            "content" => $content,
            "point" => $point,
        ];

        return redirect()->back();
    }
}
