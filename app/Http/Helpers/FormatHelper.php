<?php
  
function dateFormat($date){
    return (object) [
        "date" =>\Carbon\Carbon::parse($date)->format("D, M Y"),
        "diff" => $date->diffForHumans()
    ];
}

function defaultImage($module){
    switch ($module) {
        case 'user':
            return [
                "file_path" => 'assets/default',
                "file_name" => '453152464_orig (2).jpg',
                "file_mime" => 'image/jpeg',
                "file_full_path" => Illuminate\Support\Facades\Storage::disk('gcs')->url('mission/image/4bef8e8b-1436-4724-bf71-0f01d140a50e.jpeg')
            ];
            break;
        
        default:
            # code...
            break;
    }
}

function getPublicFile($path,$file){
    return  Illuminate\Support\Facades\Storage::disk('gcs')->url($path.'/'.$file);
}