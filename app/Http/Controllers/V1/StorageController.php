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
        $store = new StorageManager;
        $store = $store->uploadFile("mission",$request->file('file'));   
        return (new ResponseTransformer)->toJson(200,"Success",$store);
    } 
}
