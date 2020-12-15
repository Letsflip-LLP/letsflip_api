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

use App\Http\Models\LikeModel;
use App\Http\Models\MissionReportModel;
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
            $storage = new StorageManager;

            if($request->thumbnail != null){
                $thumb_upload = new StorageManager;
                $thumb_upload = $thumb_upload->uploadFile("mission/thumbnail",$request->file('thumbnail'));    
                $thumbnail = $thumb_upload;
            }

            $storage = $storage->uploadFile("mission",$request->file('file')); 
             
            $mission_id         = Uuid::uuid4();
            $mission_content_id = Uuid::uuid4();
    
            // SAVE MISSION
            $mission            = new MissionModel; 
            $mission->id        = $mission_id;
            $mission->user_id   = $this->user_login->id;
            $mission->title     = $request->title; 
            $mission->text      = $request->text; 
            $mission->type      = $request->type;
            $mission->status    = 1;
            $mission->default_content_id    =  $mission_content_id;

            if($thumbnail != null){
                $mission->image_path   = $thumbnail->file_path; 
                $mission->image_file   = $thumbnail->file_name;
            }

            $save1 = $mission->save();
    
            // SAVE DEFAULT CONTENT MISSION 
            $mission_content                = new MissionContentModel; 
            $mission_content->id            = $mission_content_id;
            $mission_content->mission_id    = $mission_id;
            $mission_content->file_path     = $storage->file_path;
            $mission_content->file_name     = $storage->file_name;
            $mission_content->file_mime     = $storage->file_mime;
            $save2 = $mission_content->save();
    
            if(!$save1 || !$save2 ) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'),$mission);

        } catch (\exception $exception){
         
            Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getMission(Request $request){ 

        DB::beginTransaction();

        try {

            $mission = new MissionModel;
            
            if($request->filled('search'))
                $mission = $mission->where('title','LIKE','%'.$request->search.'%')->orWhere('text','LIKE','%'.$request->search.'%');

            if($request->filled('order_by')){
                $order_by = explode('-',$request->order_by); 

                if($order_by[0] == 'created_at')
                    $mission = $mission->orderBy($order_by[0],$order_by[1]);

                if($order_by[0] == 'trending')
                    $mission = $mission->orderBy("created_at","DESC");
 
            }else{
                $mission = $mission->orderBy('created_at','DESC'); 
            }
                
            
            $mission = $mission->paginate($request->input('per_page',10)); 

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


    public function addResponeMission(Request $request){

        DB::beginTransaction();

        try {
           
            $thumbnail  = null; 
            $storage = new StorageManager;

            if($request->thumbnail != null){
                $thumb_upload = new StorageManager;
                $thumb_upload = $thumb_upload->uploadFile("mission/thumbnail",$request->file('thumbnail'));    
                $thumbnail = $thumb_upload;
            }

            $storage = $storage->uploadFile("mission",$request->file('file'));   
             
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
            }

            $save1 = $mission_respone->save();
    
            // SAVE DEFAULT CONTENT MISSION 
            $mission_content                = new MissionResponeContentModel; 
            $mission_content->id            = $mission_respone_content_id;
            $mission_content->mission_response_id = $mission_respone_id;
            $mission_content->file_path     = $storage->file_path;
            $mission_content->file_name     = $storage->file_name;
            $mission_content->file_mime     = $storage->file_mime;
            $save2 = $mission_content->save();
    
            if(!$save1 || !$save2 ) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

        DB::commit();
    
            return (new MissionTransformer)->detail(200,__('messages.200'), $mission_respone );

        } catch (\exception $exception){
         
            Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getResponeMission(Request $request){

        DB::beginTransaction();

        try {
             
            $respone_mission = new MissionResponeModel;
            $respone_mission = $respone_mission->where('mission_id',$request->mission_id)->get(); 

            DB::commit();
        
                return (new MissionTransformer)->list(200,__('messages.200'),$respone_mission);

        } catch (\exception $exception){
         
            Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
}
