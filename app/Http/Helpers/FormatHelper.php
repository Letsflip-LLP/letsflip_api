<?php
  
function dateFormat($date){
    $sort = $date->diffForHumans(null,true);
    $sort = explode(' ',$sort);
    return (object) [
        "date" =>\Carbon\Carbon::parse($date)->format("D, M Y"),
        "diff" => $date->diffForHumans(),
        "sort_diff" => $sort[0]." ".strtoupper($sort[1][0]),
    ];
}

function defaultImage($module){
    switch ($module) {
        case 'user':
            return [
                "file_path" => 'assets/default',
                "file_name" => 'user-default-icon.png',
                "file_mime" => 'image/png',
                "file_full_path" => "https://storage.googleapis.com/staging_lets_flip/live/assets/user-default-icon.png"
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