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
            $storage = new StorageManager;
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
            $mission->default_content_id      =  $mission_content_id;
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
}
