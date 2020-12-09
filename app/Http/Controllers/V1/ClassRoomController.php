<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\ClassRoomTransformer; 
use App\Http\Models\ClassRoomModel; 
use Ramsey\Uuid\Uuid;
use DB;

class ClassRoomController extends Controller
{
    //
    private $user_login;

    public function __construct(){
        $this->user_login =  auth('api')->user();
    }
    

    public function addClassRoom(Request $request){

        DB::beginTransaction();

        try {
            $storage = new StorageManager;
            $storage = $storage->uploadFile("mission",$request->file('file'));   
             
            $classroom_id   = Uuid::uuid4(); 
            
            $class_room                = new ClassRoomModel;
            $class_room->id            = $classroom_id; 
            $class_room->title         = $request->title;
            $class_room->user_id       = $this->user_login->id;
            $class_room->text          = $request->text;
            $class_room->file_path     = $storage->file_path;
            $class_room->file_name     = $storage->file_name;
            $class_room->file_mime     = $storage->file_mime;  
            $class_room->type          = $request->type;  

            if(!$class_room->save()) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

        DB::commit();
    
            return (new ClassRoomTransformer)->detail(200,__('message.200'),$class_room);

        } catch (\exception $exception){
         
            Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getClassRoom(Request $request){

        DB::beginTransaction();

        try {
            $class_room = new ClassRoomModel;
            
            if($request->filled('search'))
                $class_room = $class_room->where('title','LIKE','%'.$request->search.'%')->orWhere('text','LIKE','%'.$request->search.'%');

            $class_room = $class_room->paginate($request->input('per_page',10)); 

        DB::commit();
    
            return (new ClassRoomTransformer)->list(200,__('message.200'),$class_room);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
}
