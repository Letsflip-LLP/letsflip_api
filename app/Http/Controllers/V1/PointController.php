<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\MissionTransformer; 
use App\Http\Transformers\V1\QuickScoreTransformer; 
use App\Http\Transformers\V1\AnswerTransformer; 
use App\Http\Models\MissionModel;
use App\Http\Models\MissionContentModel;
use App\Http\Models\MissionResponeModel;
use App\Http\Models\MissionResponeContentModel;
use App\Http\Models\TagModel; 
use App\Http\Models\LikeModel;
use App\Http\Models\MissionReportModel;
use App\Http\Models\NotificationModel;
use App\Http\Models\ClassRoomModel;
use App\Http\Models\UserPointsModel; 
use App\Http\Models\MissionQuestionModel; 
use App\Http\Models\MissionAnswerModel;
use App\Http\Models\ReviewModel;
use App\Http\Models\GradeOverviewModel;

use Ramsey\Uuid\Uuid;
use DB;
use App\Http\Libraries\Notification\NotificationManager;
use Jenssegers\Agent\Agent; 

class PointController extends Controller
{
    //
    private $user_login;

    public function __construct(){
        $this->user_login =  auth('api')->user();
    }
    
    
    public function pointOnAddRespone($mission_respone){
        DB::beginTransaction();

        try { 
            $mission_detail = $mission_respone->Mission; 
            $user_mission = $mission_detail->User;
            $user_response = $mission_respone->User; 

            // ADD POINT
            UserPointsModel::insert([
                [
                    "user_id_to" => $user_response->id,
                    "mission_id" => $mission_detail->id,
                    "respone_id" => $mission_respone->id,
                    "value" => $earn_point =  env('POINT_TYPE_3'),
                    "type" => 3,
                    "id" => $point_id = Uuid::uuid4(),
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ],
                [
                    "user_id_to" => $mission_detail->user_id,
                    "mission_id" => $mission_detail->id,
                    "respone_id" => $mission_respone->id,
                    "value" => $earn_point2= env('POINT_TYPE_4'),
                    "type" => 4,
                    "id" => $point_id2 = Uuid::uuid4(),
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]
            ]); 

            // NOTIF FOR OWN OF MISSION 
            NotificationManager::addNewNotification($user_response->id,$user_mission->id,
                [
                    "respone_id" => $mission_respone->id,
                    "mission_id"  => $mission_respone->mission_id
                ],1
            );
 
            // FOR RESPONDEND
            $notif_mission = NotificationManager::addNewNotification(null,$user_response->id,[
                "mission_id" => $mission_detail->id,
                "respone_id" => $mission_respone->id,
                "point_id" => $point_id
            ],11,[
            "type"=> "point",
            "payload"=> [
                "type"=> "point",
                "title"=>"CONGRATULATIONS!",
                "text" => "You have earned ".$earn_point." PTS for Created Response!",
                "value" => $earn_point
            ]
            ]);

            // FOR MISSION OWNER
            $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$mission_detail->user_id,[
                "mission_id" => $mission_detail->id,
                "respone_id" => $mission_respone->id,
                "point_id" => $point_id2
                ],11,[
                "type"=> "point",
                "payload"=> [
                    "type"=> "point",
                    "title"=>"CONGRATULATIONS!",
                    "text" => "You have earned ".$earn_point2." PTS for Created Response!",
                    "value" => $earn_point2
                ]
            ]);

            DB::commit();
    
            return true;

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function pointOnAddMission($mission_detail){
 
        DB::beginTransaction();

        try {  
            $mission_detail     = $mission_detail;
            $classroom_detail   = $mission_detail->ClassRoomTag ? $mission_detail->ClassRoomTag[0] : null;

            // INSERT POINT FOR MISSION CREATOR 
            $check  = UserPointsModel::where( [ "user_id_to" => $mission_detail->user_id,   "mission_id" => $mission_detail->id]);
            $check  = $check->whereIn('type',[1,2]);
            $check  = $check->first();
            
            $point_status = 2;

            if($check == null){
                $is_first           = UserPointsModel::where('user_id_to',$mission_detail->user_id)->where('type',1)->first() ? false : true;
                $insert_point       = UserPointsModel::insert(
                [
                    "user_id_to" => $mission_detail->user_id, 
                    "mission_id" => $mission_detail->id,
                    "type" => $is_first ? 1 : 2,
                    "value" => $earn_point = $is_first ? env('POINT_TYPE_1') : env('POINT_TYPE_2'),
                    "id" => $point_id = Uuid::uuid4(),
                    "status" => $point_status = 1//$classroom_detail->user_id != $mission_detail->user_id && $classroom_detail->type != 1 ? 2 : 1
                ]);
            
                // IF USER TAG OTHER NON PUBLIC CLASSROOM CREATOR WILL GET NOTIFICATION
                if($point_status != 1){
                    $notif_mission = NotificationManager::addNewNotification(null,$mission_detail->user_id,[
                        "mission_id" => $mission_detail->id,
                        "classroom_id" => $classroom_detail->id,
                    ],19);
                }else{
                    // SEND NOTIFICATION TO USER CREATOR GET POINT
                    $notif_mission = NotificationManager::addNewNotification(null,$mission_detail->user_id,[
                        "mission_id" => $mission_detail->id,
                        "point_id" => $point_id
                    ],11,[
                        "type"=>"point",
                        "payload"=>[
                            "title"=>"CONGRATULATIONS!",
                            "text"=> $is_first ? "You have earned ".$earn_point." PTS for your first Mission!" : "You have earned ".$earn_point." PTS for Created Mission!",
                            "value"=> $earn_point ]
                    ]);
                            
                    $classroom_owner_notif = NotificationManager::addNewNotification($mission_detail->user_id,$classroom_detail->user_id,[
                        "mission_id" => $mission_detail->id,
                        "classroom_id" => $classroom_detail->id
                    ],2);
                }
            }
               
        
        DB::commit();

            return true;
            
        } catch (\exception $exception){
            
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
}
