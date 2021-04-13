<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer; 

class StorageController extends Controller
{
    
    private static $autoRatio = '';

    public function __construct(){
        
    }

    //
    public function uploadFile(Request $request){
        $return       = new \stdClass();
        $storage      = new StorageManager;
        $storage      = $storage->uploadFile($request->module,$request->file('file')); 
        $return->thumbnail = null ;
 
        if($request->thumbnail == null && ($request->module== "respone" || $request->module== "mission"))
            return (new ResponseTransformer)->toJson(400,__('messages.400'),["thumbnail" => ["The thumbnail field is required"]]);


        if($request->thumbnail != null){
            $thumb_upload = new StorageManager;
            $thumb_upload = $thumb_upload->uploadFile($request->module."/thumbnail",$request->file('thumbnail'));    
            $thumbnail = $thumb_upload;  

            $return->thumbnail =(object) [
                'image_path' => $thumbnail->file_path,
                'image_file' => $thumbnail->file_name,
                'image_full_path' => getPublicFile($thumbnail->file_path,$thumbnail->file_name)
            ];
        } 
        
        $return->file_path     = $storage->file_path;
        $return->file_name     = $storage->file_name;
        $return->file_mime     = $storage->file_mime;
        $return->file_full_path= getPublicFile($storage->file_path,$storage->file_name);

        return (new ResponseTransformer)->toJson(200,"Success",$return);
    } 
}
