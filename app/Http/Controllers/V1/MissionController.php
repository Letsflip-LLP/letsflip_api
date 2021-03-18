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

use Ramsey\Uuid\Uuid;
use DB;
use App\Http\Libraries\Notification\NotificationManager;
use Jenssegers\Agent\Agent; 

class MissionController extends Controller
{
    //
    private $user_login;

    public function __construct(){
        $this->user_login =  auth('api')->user();
    }
    

    public function addMission(Request $request){ 

        DB::beginTransaction();

        try {
            $thumbnail  = null; 
 
            if($request->thumbnail != null){
                $thumb_upload = new StorageManager;
                $thumb_upload = $thumb_upload->uploadFile("mission/thumbnail",$request->file('thumbnail'));    
                $thumbnail = $thumb_upload;
            }
 
            $mission_id         = Uuid::uuid4();
            $mission_content_id = Uuid::uuid4();
    
            // SAVE MISSION
            $mission            = new MissionModel; 
            $mission->id        = $mission_id;
            $mission->difficulty_level  = $request->difficulty_level;
            $mission->user_id   = $this->user_login->id;
            $mission->title     = $request->title; 
            $mission->text      = $request->text; 
            $mission->type      = $request->input('type',1);
            $mission->status    = $request->input('status',1);
            $mission->default_content_id    =  $mission_content_id;

            if($thumbnail != null){
                $mission->image_path   = $thumbnail->file_path;
                $mission->image_file   = $thumbnail->file_name;
            }else{
                $mission->image_path   = $request->thumbnail_file_path;
                $mission->image_file   = $request->thumbnail_file_name;
            }

            $save1 = $mission->save();
    
            // SAVE DEFAULT CONTENT MISSION 

            $mission_content                = new MissionContentModel; 
            $mission_content->id            = $mission_content_id;
            $mission_content->mission_id    = $mission_id;

            if($request->filled('file')){
                $storage = new StorageManager;
                $storage = $storage->uploadFile("mission",$request->file('file'));
                $mission_content->file_path     = $storage->file_path;
                $mission_content->file_name     = $storage->file_name;
                $mission_content->file_mime     = $storage->file_mime;
            }else{
                $mission_content->file_path     = $request->file_path;
                $mission_content->file_name     = $request->file_name;
                $mission_content->file_mime     = $request->file_mime;
            }

            $save2 = $mission_content->save();
    
            if(!$save1 || !$save2 ) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

                               
            if($request->filled('tag_classroom_ids')){
                $classroom_id = explode(',',$request->tag_classroom_ids);
                $insert_class_tags = [];
                foreach($classroom_id as $cl_id){
                    $class_room_detail = ClassRoomModel::where('id',$cl_id)->first();

                    if($class_room_detail){
                        $temp_id[$cl_id] = Uuid::uuid4(); 
                        $tag_model = new TagModel; 
                        $tag_model->firstOrCreate(
                            [
                                "module" => "mission", "module_id" => $mission_id , "foreign_id" =>  $cl_id , "type" => 1
                            ],
                            [
                                "id" => Uuid::uuid4()
                            ]
                        );

                        //NOTIF FOR OWN OF CLASSROM
                        $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$class_room_detail->user_id,[
                            "mission_id" => $mission_id,
                            "classroom_id" => $class_room_detail->id
                        ],2); 
                    }
                }
            }


            if($request->filled('tag_user_ids')){
                $user_ids = explode(',',$request->tag_user_ids);
                $insert_class_tags = [];
                foreach($user_ids as $u_id){
                    $temp_id[$u_id] = Uuid::uuid4(); 

                    $tag_model = new TagModel; 
                    $tag_model->firstOrCreate(
                        [
                            "module" => "mission", "module_id" => $mission_id , "foreign_id" =>  $u_id , "type" => 2
                        ],
                        [
                            "id" => Uuid::uuid4()
                        ]
                    );
                }
            }


            // QUICK SCORE
            if($request->filled('quick_scores')){
                $this->_insertQuickScore($request->quick_scores,$mission_id);
            }

            // LEARNING JOURNEY
            if($request->filled('learning_journey')){
                $this->_insertLearningJourney($request->learning_journey,$mission_id);
            }

    
            //NOTIF FOR OWN OF CLASSROM
            if($mission->status == 1){
                $is_first = UserPointsModel::where('user_id_to',$this->user_login->id)->where('type',1)->first() ? false : true;
                UserPointsModel::insert([
                    "user_id_to" => $this->user_login->id,
                    "mission_id" => $mission_id,
                    "value" => $earn_point = $is_first ? env('POINT_TYPE_1') : env('POINT_TYPE_2'),
                    "type" => $is_first ? 1 : 2,
                    "id" => $point_id = Uuid::uuid4()
                ]); 
    
                $notif_mission = NotificationManager::addNewNotification(null,$this->user_login->id,[
                    "mission_id" => $mission_id,
                    "point_id" => $point_id
                ],11,[
                    "type"=>"point",
                    "payload"=>[
                        "title"=>"CONGRATULATIONS!",
                        "text"=> $is_first ? "You have earned ".$earn_point." PTS for your first Mission!" : "You have earned ".$earn_point." PTS for Created Mission!",
                        "value"=> $earn_point ]
                ]);
            }
        
        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'),$mission);

        } catch (\exception $exception){
         
            // Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    private function _insertLearningJourney($data,$mission_id){
        $template = config('static_db.question_template'); 
        $template = $template['learning_journey'];
        $quest_ids = $data;

        $include = [];
        foreach( $template as $dat ){
           if(in_array($dat['id'],$data)){
            $quest_id   = Uuid::uuid4();
            $include[] = [
                "id"         => $quest_id,
                "mission_id" => $mission_id,
                "title"      => $dat['title'],
                "text"       => $dat['title'],
                "question_template_id" => $dat['id'],
                "question_type" => 2,
                "type" => 2
            ];
           }
         } 

         $model = new MissionQuestionModel;
         $model = $model->insert($include);
    }

    private function _insertQuickScore($data,$mission_id){
        $datas = [];
        foreach($data as $q){
            $quest_id   = Uuid::uuid4();
            $datas[] = [
                "id"         => $quest_id,
                "mission_id" => $mission_id,
                "title"      => $q['title'],
                "text"       => $q['title'],
                "option1"    => $q['options'] && isset($q['options'][0]) ? $q['options'][0]["name"] : null,
                "option2"    => $q['options'] && isset($q['options'][1]) ? $q['options'][1]["name"] : null,
                "option3"    => $q['options'] && isset($q['options'][2]) ? $q['options'][2]["name"] : null,
                "option4"    => $q['options'] && isset($q['options'][3]) ? $q['options'][3]["name"] : null,
                "option5"    => $q['options'] && isset($q['options'][4]) ? $q['options'][4]["name"] : null,
                "option6"    => $q['options'] && isset($q['options'][5]) ? $q['options'][5]["name"] : null,
                "option7"    => $q['options'] && isset($q['options'][6]) ? $q['options'][6]["name"] : null,
                "correct_option" => $q['correct_answer'] ? "option".$q['correct_answer'] : null,
                "question_type" => $q['type'],
                "type" => 1
            ];
        }
        
        $model = new MissionQuestionModel;
        $model = $model->insert($datas);
    }

    public function addResponeMission(Request $request){

        DB::beginTransaction();

        try {
           
            $check = MissionResponeModel::where('user_id',$this->user_login->id)->where('mission_id',$request->mission_id)->first();
            
            if($check != null)
                return (new ResponseTransformer)->toJson(400,"You have responed this mission before",(object) ['error' => ["You have responed this mission before"]]);

            $mission_detail = MissionModel::where('id',$request->mission_id)->first();

            $thumbnail  = null; 

            if($request->thumbnail != null){
                $thumb_upload = new StorageManager;
                $thumb_upload = $thumb_upload->uploadFile("mission/thumbnail",$request->file('thumbnail'));    
                $thumbnail = $thumb_upload;
            }

            $mission_respone_id         = Uuid::uuid4();
            $mission_respone_content_id = Uuid::uuid4();
    
            // SAVE MISSION
            $mission_respone            = new MissionResponeModel; 
            $mission_respone->id        = $mission_respone_id;
            $mission_respone->user_id   = $this->user_login->id;
            $mission_respone->mission_id= $request->mission_id;
            $mission_respone->title     = $request->title; 
            $mission_respone->text      = $request->text; 
            $mission_respone->type      = $request->input('type',1);
            $mission_respone->status    = $request->input('status',1);
            $mission_respone->default_content_id = $mission_respone_content_id;

            if($thumbnail != null){
                $mission_respone->image_path   = $thumbnail->file_path; 
                $mission_respone->image_file   = $thumbnail->file_name;
            }else{
                $mission_respone->image_path   = $request->thumbnail_file_path;
                $mission_respone->image_file   = $request->thumbnail_file_name;
            }

            $save1 = $mission_respone->save();
    
            // SAVE DEFAULT CONTENT MISSION 
            $mission_content                = new MissionResponeContentModel;
            $mission_content->id            = $mission_respone_content_id;
            $mission_content->mission_response_id = $mission_respone_id;
         
            if($request->filled('file')){
                $storage = new StorageManager;
                $storage = $storage->uploadFile("mission",$request->file('file'));
                $mission_content->file_path     = $storage->file_path;
                $mission_content->file_name     = $storage->file_name;
                $mission_content->file_mime     = $storage->file_mime;
            }else{
                $mission_content->file_path     = $request->file_path;
                $mission_content->file_name     = $request->file_name;
                $mission_content->file_mime     = $request->file_mime;
            }

            $save2 = $mission_content->save();
    
            if(!$save1 || !$save2 ) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

            // NOTIF FOR OWN OF MISSION 
            $notif_new_respone = NotificationManager::addNewNotification($this->user_login->id,$mission_detail->user_id,
                            [
                                "respone_id" => $mission_respone_id,
                                "mission_id"  => $mission_detail->id
                            ],1);

            // ADD POINT
            UserPointsModel::insert([
                [
                    "user_id_to" => $this->user_login->id,
                    "mission_id" => $mission_detail->id,
                    "respone_id" => $mission_respone_id,
                    "value" => $earn_point =  env('POINT_TYPE_3'),
                    "type" => 3,
                    "id" => $point_id = Uuid::uuid4(),
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ],
                [
                    "user_id_to" => $mission_detail->user_id,
                    "mission_id" => $mission_detail->id,
                    "respone_id" => $mission_respone_id,
                    "value" => $earn_point2= env('POINT_TYPE_4'),
                    "type" => 4,
                    "id" => $point_id2 = Uuid::uuid4(),
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]
            ]); 

            // FOR RESPONDEND
            $notif_mission = NotificationManager::addNewNotification(null,$this->user_login->id,[
                "mission_id" => $mission_detail->id,
                "respone_id" => $mission_respone_id,
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
                "respone_id" => $mission_respone_id,
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

                    
            $answer = new MissionAnswerModel;
            $answer = $answer->where('user_id',$this->user_login->id)->wherehas('Question',function($q) use ($mission_detail){
                $q->where('mission_questions.mission_id',$mission_detail->id);
            })->get();

            $point = 0;

            if($answer){
                foreach($answer as $ans){ 
                    if($ans->Question->type == 2 && $ans->is_true == 1)
                        $point = $point + env('TYPE_2_TRUE');

                    if($ans->Question->type == 2 && $ans->is_true == 0)
                        $point = $point + env('TYPE_2_FALSE');
                    
                    if($ans->Question->type == 1 && $ans->is_true == 1)
                        $point = $point + env('TYPE_1_TRUE');

                    if($ans->Question->type == 1 && $ans->is_true == 0)
                        $point = $point + env('TYPE_1_FALSE');
                }
                // insert point for answer question

                // ADD POINT
                UserPointsModel::insert([
                    [
                        "user_id_to" => $this->user_login->id,
                        "mission_id" => $mission_detail->id,
                        "respone_id" => $mission_respone_id,
                        "value" => $answer_point =  $point,
                        "type" => 5,
                        "id" => Uuid::uuid4(),
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                ]); 
            }



        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'), $mission_respone );

        } catch (\Exception $exception){

            DB::rollBack();

            if(isset($storage) && isset($storage->file_path) && isset($storage->file_name))
                Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getMission(Request $request){ 

        DB::beginTransaction();

        try {

            $mission = new MissionModel;

            if(!$this->user_login || !$this->user_login->id)
                $mission = $mission->where('status',1);
        
            if(!$request->filled('user_id') || ($this->user_login && $request->filled('user_id') != $this->user_login->id))
                $mission = $mission->where('status',1);
                
            if($request->filled('search'))
                $mission = $mission->where('title','LIKE','%'.$request->search.'%')->orWhere('text','LIKE','%'.$request->search.'%');

            if($request->filled('classroom_id'))
                $mission = $mission->whereHas('ClassRoomTag',function($q) use($request) {
                    $q->where('foreign_id',$request->classroom_id);
                });

            if($request->filled('order_by')){
                $order_by = explode('-',$request->order_by); 

                if($order_by[0] == 'created_at')
                    $mission = $mission->orderBy($order_by[0],$order_by[1]);

                if($order_by[0] == 'trending'){
                    $mission = $mission->withCount('Respone')->orderBy('respone_count', 'desc');
                }
            }else{
                $mission = $mission->orderBy('created_at','DESC'); 
            }
                
            if($request->filled('user_id') && !$request->filled('module'))
                $mission = $mission->where('user_id',$request->user_id);
            
            if($request->filled('user_id') && $request->filled('module')){
                if($request->module == 'response'){
                    $mission = $mission->whereHas('Respone',function($q) use ($request){
                        $q->where('user_id',$request->user_id);
                    });
                }

                if($request->module == 'all'){
                    $mission = $mission->where('user_id',$request->user_id);
                    $mission = $mission->orWhereHas('Respone',function($q) use ($request){
                        $q->where('user_id',$request->user_id);
                    });
                }

            }

            $perPage = $request->input('per_page',10);
            // if($perPage < 10) 
            //     $perPage = 20;
                
            $mission = $mission->paginate($perPage); 
            // $mission = $mission->paginate(30);

        DB::commit();
    
            return (new MissionTransformer)->list(200,__('messages.200'),$mission);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getMissionDetail(Request $request){

        DB::beginTransaction();

        try {

            $mission = new MissionModel;
            $mission = $mission->where('id',$request->id)->first();
 
            if($mission == null)
                return (new MissionTransformer)->list(400,__('message.404'),$mission);

        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'),$mission);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function likeActionMission(Request $request){
 
        DB::beginTransaction();

        try {

       $model1 = new LikeModel;
       $model2 = new LikeModel;
       $model1 = $model1->where('user_id',$this->user_login->id);

       if($request->filled("mission_id")){
             $model1 = $model1->where('mission_id',$request->mission_id)->first(); 
             $model2->mission_id = $request->mission_id;
              
             // Notify to user  
             if($model1 == null){
                $mission_detail = MissionModel::where('id',$request->mission_id)->first();
                $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$mission_detail->user_id,
                [ 
                    "mission_id"  => $request->mission_id
                ],3);
             } 
       }

        if($request->filled("mission_comment_id")){
            $model1 = $model1->where('mission_comment_id',$request->mission_comment_id)->first();
            $model2->mission_comment_id = $request->mission_comment_id;
        }
        
        if($request->filled("mission_respone_id")){
            $model1 = $model1->where('mission_respone_id',$request->mission_respone_id)->first();
            $model2->mission_respone_id = $request->mission_respone_id;

            // Notify to user
           if($model1 == null){
                $res_mission_detail = MissionResponeModel::where('id',$request->mission_respone_id)->first();
                $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$res_mission_detail->user_id,
                [ 
                    "mission_id"  => $res_mission_detail->mission_id,
                    "respone_id"  => $request->mission_respone_id
                ],4);
            }
        }

        if($request->filled("mission_comment_respone_id")){
            $model1 = $model1->where('mission_respone_comment_id',$request->mission_comment_respone_id)->first();
            $model2->mission_respone_comment_id = $request->mission_comment_respone_id;
        }

        if($request->filled("classroom_id")){
            $model1 = $model1->where('classroom_id',$request->classroom_id)->first();
            $model2->classroom_id = $request->classroom_id;
        }

        if($model1 == null){
            $model2->id      = Uuid::uuid4();
            $model2->user_id = $this->user_login->id;
            $model2->save();
            $action = "add";
        }else{
            $model1->delete();
            $action = "delete";
        }
        
        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),$action);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function reportActionContent(Request $request){
 
        DB::beginTransaction();

        try {

            $model1 = new MissionReportModel;
            $model2 = new MissionReportModel;
            $model1 = $model1->where('user_id',$this->user_login->id);
     
            if($request->filled("mission_id")){
                  $model1 = $model1->where('mission_id',$request->mission_id);
                  $model2->mission_id = $request->mission_id;
            }
     
             if($request->filled("mission_comment_id")){
                 $model1 = $model1->where('mission_comment_id',$request->mission_comment_id);
                 $model2->mission_comment_id = $request->mission_comment_id;
             }
             
             if($request->filled("mission_respone_id")){
                 $model1 = $model1->where('mission_respone_id',$request->mission_respone_id);
                 $model2->mission_respone_id = $request->mission_respone_id;
             }
     
             if($model1->first() == null){
                 $model2->id      = Uuid::uuid4();
                 $model2->user_id = $this->user_login->id;
                 $model2->title   = $request->title;
                 $model2->text    = $request->text;

                 $model2->save(); 
             }else{
                $model1->title   = $request->title;
                $model1->text    = $request->text;
                $model1->update([
                    "title" => $request->title,
                    "text" => $request->text
                ]); 
             }
        
        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
 

    public function getResponeMission(Request $request){

        DB::beginTransaction();

        try {
             
            $respone_mission = new MissionResponeModel; 
            
            if($request->filled('mission_id'))
                $respone_mission = $respone_mission->where('mission_id',$request->mission_id);

            if($request->filled('user_id'))
                $respone_mission = $respone_mission->where('user_id',$request->user_id);

            $respone_mission = $respone_mission->where('status',1);

            $respone_mission = $respone_mission->orderBy('created_at','DESC')->get(); 

            DB::commit();
        
                return (new MissionTransformer)->list(200,__('messages.200'),$respone_mission);

        } catch (\exception $exception){
         
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function deleteMission(Request $request){

        DB::beginTransaction();

        try {

            $mission = new MissionModel;
            $mission = $mission->where('id',$request->mission_id)->where('user_id' , $this->user_login->id)->first();
             
            if($mission == null)
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERRDELMIS1");

            if(!$mission->delete())
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERRDELMIS2");


            // REMOVE POINT
            $point = UserPointsModel::where('mission_id',$request->mission_id)
                                    ->where('user_id_to',$this->user_login->id)
                                    ->whereIn('type',[1,2])
                                    ->first();

            if($point){
                $point_detail = $point;
                // dd($point);
                $point->delete();
    
                // FOR DELETED MISSION
                $add_notif = NotificationManager::addNewNotification(null,$this->user_login->id,[
                    "mission_id" => $mission->id,
                    "point_id" => $point_detail->id
                ],12);
            }

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }


    public function editMission(Request $request){

        DB::beginTransaction();

        try {

            $mission = new MissionModel;
            $mission = $mission->where('id',$request->mission_id)->where('user_id' , $this->user_login->id)->first();
             
            if($mission == null)
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERREDMS1");


            if($request->filled('status'))
                $mission->status = $request->status;

            if($request->filled('title'))
                $mission->title = $request->title;
            
            if($request->filled('text'))
                $mission->text = $request->text;
            
            if(!$mission->save())
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERREDMS2");

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function deleteResponeMission(Request $request){

        DB::beginTransaction();

        try {

            $mission_respone = new MissionResponeModel;
            $mission_respone = $mission_respone->where('id',$request->mission_respone_id)->first();
             
            if($mission_respone == null)
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERRDELMIS1");
                
                
            if(($mission_respone->user_id != $this->user_login->id && $mission_respone->Mission->user_id != $this->user_login->id))
                return (new ResponseTransformer)->toJson(400,__('message.401'),"ERRDELRE01");


            if(!$mission_respone->delete())
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERRDELMIS2");

           // REMOVE POINT
            $point = UserPointsModel::where('respone_id',$request->mission_respone_id)
                                    ->where('user_id_to',$this->user_login->id)
                                    ->whereIn('type',[3])
                                    ->first();         

            if($point){
                $point_detail = $point; 
                $point->delete(); 
                // FOR DELETED RESPONE
                $add_notif = NotificationManager::addNewNotification(null,$this->user_login->id,[
                    "respone_id" => $request->mission_respone_id,
                    "mission_id" => $mission_respone->mission_id,
                    "point_id" => $point_detail->id
                ],13);
            }

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function openApp(Request $request){
        $data = null;

        if($request->mission_id)
            $data = MissionModel::where('id',$request->mission_id)->first(); 


        if($request->mission_respone_id)
            $data = MissionResponeModel::where('id',$request->mission_respone_id)->first(); 


        if($data == null) abort(404);

        $redirect_url   = 'https://getletsflip.com';
        $deepLinkUrl    = 'letsflip://'.$request->getHost().'/open-app/mission/'.$request->mission_id;

        if($request->mission_respone_id)
            $deepLinkUrl .='?mission_respone_id='.$request->mission_respone_id;
        
        $agent = new Agent();
        
        if($agent->isAndroidOS())
            $redirect_url = env('ANDROID_PLAYSTORE_URL');//redirect(env('ANDROID_PLAYSTORE_URL'));

        if($agent->is('iPhone') || $agent->platform() == 'IOS' ||  $agent->platform() == 'iOS' || $agent->platform() == 'ios' )
            $redirect_url = env('IOS_APP_STORE_URL');//return redirect(env('IOS_APP_STORE_URL'));

        return view('open-app.share-meta',
            [
                'redirect_url' => $redirect_url,
                'deeplink_url' => $deepLinkUrl,
                'title'=> $data->title,
                'description'=>$data->text,
                'og_image'=>Storage::disk('gcs')->url($data->image_path.'/'.$data->image_file)
            ]); 
    }


    public function editResponeMission(Request $request){

        DB::beginTransaction();

        try {

            $mission_respone = new MissionResponeModel;
            $mission_respone = $mission_respone->where('id',$request->mission_respone_id)->where('user_id',$this->user_login->id)->first();
             
            if($mission_respone == null)
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERREDRES001");
    
            if($request->filled('status'))
                $mission_respone->status = $request->status;

            if($request->filled('title'))
                $mission_respone->title = $request->title;
            
            if($request->filled('text'))
                $mission_respone->text = $request->text;

            if(!$mission_respone->save())
                return (new ResponseTransformer)->toJson(400,__('message.404'),"ERREDRES002");
 
        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getQuestionList(Request $request){
        $model = new MissionQuestionModel;
        $model = $model->where('mission_id',$request->mission_id);
        $model = $model->with('Answer',function($q1){
            $q1->orderBy('index');
        });
        if($request->filled('type'))
            $model = $model->where('type',$request->type);

        $model = $model->get(); 
        return (new QuickScoreTransformer)->list(200,__('messages.200'),$model); 
    }

    public function getQuestionTemplate(Request $request){
        $data = [];
        $template = config('static_db.question_template'); 
        
        if($request->filled('type') && $request->type == 2)
            $data = $template['learning_journey'];

        return (new ResponseTransformer)->toJson(200,__('message.200'),$data); 
    }

    public function submitAnswerOfQuestion(Request $request){
        DB::beginTransaction();

        try {
            $answer_id       = null;
            $mission_detail  = MissionModel::where('id',$request->mission_id)->first();
            $question_detail = MissionQuestionModel::where('id',$request->question_id)->first();
            
            // CHECK SUBMIT RESPONES 
            if($mission_detail->Respone->where('user_id',$this->user_login->id)->where('status',1)->first())
                return (new ResponseTransformer)->toJson(400,__('messages.404'),["mission_id" => "You have been response this mission before"]);
            
            $model = new MissionAnswerModel;
            
            $multi_c = [
                'option1',
                'option2',
                'option3',
                'option4',
                'option5',
                'option6',
                'option7',
            ];
            
            $payload_current_ = (object) [
                "question_detail" => $question_detail,
                "mission_detail"  => $mission_detail,
                "answer_detail"   => $request->all()
            ];

            if($question_detail->type == 1){
                $multiple_answer = $request->answer;
                $multiple_answer = isset($multiple_answer[0]) ? $multiple_answer[0] : null;

                if(!$multiple_answer)
                    return (new ResponseTransformer)->toJson(400,__('validation.exists',["attribute" => "answer"]),["answer" => [__('validation.exists',["attribute" => "answer"])]]);

                if(!in_array($multiple_answer,$multi_c))
                    return (new ResponseTransformer)->toJson(400,__('validation.exists',["attribute" => "answer"]),["answer" => [__('validation.exists',["attribute" => "answer"])]]);
                
                $exist = MissionAnswerModel::where([
                        "user_id" => $this->user_login->id,
                        "question_id" => $request->question_id
                    ])->first(); 

                if($exist == null){
                    $insert                 = new MissionAnswerModel;
                    $insert->id             = $answer_id = Uuid::uuid4();
                    $insert->user_id        = $this->user_login->id;
                    $insert->question_id    = $request->question_id;
                    $insert->answer         = $multiple_answer;
                    $insert->is_true        = $question_detail->correct_option == $multiple_answer ? true : false;
                    $insert->payload        = json_encode($payload_current_);
                    $insert->save();
                }else{
                    $exist->user_id        = $this->user_login->id;
                    $exist->question_id    = $request->question_id;
                    $exist->answer         = $multiple_answer;
                    $exist->is_true        = $question_detail->correct_option == $multiple_answer ? true : false;
                    $exist->payload        = json_encode($payload_current_);
                    $exist->update();
                    $answer_id = $exist->id;
                }
            }

            if($question_detail->type == 2){
                if(!$request->filled('answer_id')){
                    $delete = MissionAnswerModel::where('user_id',$this->user_login->id);
                    $delete = $delete->where('question_id',$request->question_id); 
                    $delete = $delete->delete();

                    $answer = $request->answer;
                    // $answers_data = [];
                    $i = 0;
                    foreach($answer as $ans){
                        $answers_data = [
                            'id' => $answer_id = Uuid::uuid4(),
                            'index' => $i++,
                            'user_id' => $this->user_login->id,
                            'question_id' =>$request->question_id,
                            'answer' => $ans,
                            'is_true' => 1,
                            'payload' => json_encode($payload_current_)
                        ];

                        $insert = MissionAnswerModel::insert($answers_data); 
                    } 

                }
            }

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getReviewEmotions(Request $request){
        $review = config('static_db.review'); 
        $emots  = $review['emotion_list'];

        $check = null;
        if($this->user_login->id && $request->mission_id){
            $check =    new ReviewModel;
            $check =    $check->where('user_id',$this->user_login->id);
            $check =    $check->where('module_id',$request->mission_id);
            $check =    $check->where('module','missions');
            $check =    $check->first();
        } 
        
        $return = [];
        foreach($emots as $emot){
            $tmp =  $emot; 
            $tmp['selected'] = $check && $check->feeling == $emot['code'] ? true : false;
            $return[] = $tmp;
        }

        return (new ResponseTransformer)->toJson(200,__('messages.200'),$return);
    }

    public function addReview(Request $request){
        DB::beginTransaction();

        try {

            $model = new ReviewModel;
            $model = $model
                    ->where('user_id',$this->user_login->id)
                    ->where('module','missions')
                    ->where('module_id',$request->mission_id)
                    ->first();

            if($model == null){
                $insert             = new ReviewModel;
                $insert->id         = $review_id = Uuid::uuid4();
                $insert->user_id    = $this->user_login->id;
                $insert->module     = 'missions';
                $insert->module_id  = $request->mission_id;
                $insert->feeling    = $request->feeling;

                if(!$insert->save())
                    return (new ResponseTransformer)->toJson(400,__('messages.400'),false);
            }else{
                $model->feeling = $request->feeling;
                $model->update();
            }

            DB::commit();
        
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
        
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
}
