<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    //
    public function uploadFile(Request $request){
        $disk  =  Storage::disk('gcs');
        $disk  =  $disk->put('public', $request->file('file'));
        $url   =  Storage::disk('gcs');
    }
}
