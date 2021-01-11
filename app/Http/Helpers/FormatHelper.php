<?php
  
function dateFormat($date){
    $sort = $date->diffForHumans(null,true);
    $sort = explode(' ',$sort); 

    return (object) [
        "date" =>\Carbon\Carbon::parse($date)->format("D, M Y"),
        "diff" => diffFormatTableOfTime($date),
        "sort_diff" => $sort[0]." ".strtoupper($sort[1][0]),
    ];
}

function diffFormatTableOfTime($date){
    $diffSecond = \Carbon\Carbon::now()->diffInSeconds($date);
 
    switch($diffSecond){
        case $diffSecond <= 60 :
            return "Just Now";
        break;
        case $diffSecond <= 900 :
            return "Less than 15 mins ago";
        break;
        case $diffSecond <= 1800 :
            return "Less than 30 mins ago";
        break;
        case $diffSecond <= 2700 :
            return "Less than 45 mins ago";
        break;
        case $diffSecond <= 3600 :
            return "Less than 1hr ago";
        break;
        case $diffSecond <= 43200 :
            return "11 hrs";
        break;
        default:
            return \Carbon\Carbon::parse($date)->format("M d").' at '.\Carbon\Carbon::parse($date)->format("g:i A");
        break;
    }
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