<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\MissionTransformer; 
use App\Http\Models\MissionModel;
use App\Http\Models\MissionContentModel;
use App\Http\Models\MissionResponeModel;
use App\Http\Models\MissionResponeContentModel;
use App\Http\Models\TagModel; 
use App\Http\Models\LikeModel;
use App\Http\Models\MissionReportModel;
use App\Http\Models\NotificationModel;
use Ramsey\Uuid\Uuid;
use DB;

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

    

            

        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'),$mission);

        } catch (\exception $exception){
         
            // Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
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
            $mission_respone->type      = $request->type;
            $mission_respone->status    = 1;
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

            //NOTIF FOR OWN OF MISSION
            $notif_mission = new NotificationModel;
            $notif_mission->id =  Uuid::uuid4();
            $notif_mission->user_id_from   = $this->user_login->id;
            $notif_mission->user_id_to     = $mission_detail->user_id;
            $notif_mission->mission_id     = $mission_detail->id;
            $notif_mission->respone_id     = $mission_respone_id;
            $notif_mission->type           = 1;
            $notif_mission->save();

        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'), $mission_respone );

        } catch (\exception $exception){
         
            if(isset($storage) && isset($storage->file_path) && isset($storage->file_name))
                Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            
            DB::rollBack();

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
                
            if($request->filled('user_id'))
                $mission = $mission->where('user_id',$request->user_id);
             

            $mission = $mission->paginate($request->input('per_page',10)); 
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

        if($request->filled("mission_comment_respone_id")){
            $model1 = $model1->where('mission_respone_comment_id',$request->mission_comment_respone_id);
            $model2->mission_respone_comment_id = $request->mission_comment_respone_id;
        }

        if($model1->first() == null){
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


        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function openApp(){
        return redirect(env('ANDROID_PLAYSTORE_URL'));
    }
}
