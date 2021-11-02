<?php
use Illuminate\Http\Request;

function dateFormat($date){
     
    $client_timezone = request()->client_timezone;

    $sort = $date->diffForHumans(null,true);
    $sort = explode(' ',$sort); 

    return (object) [
        "date_time" =>\Carbon\Carbon::parse($date)->setTimezone($client_timezone)->format("Y-m-d H:i:s"),
        "date" =>\Carbon\Carbon::parse($date,)->setTimezone($client_timezone)->format("D, M Y"),
        "diff" => diffFormatTableOfTime($date),
        "sort_diff" => $sort[0]." ".strtolower($sort[1][0]),
    ];
}

function timeFormat($time){ 
    return (object) [
        "time" => date('H:i',strtotime($time)),
        "string" => strtotime($time)
    ];
}

function dbLocalTime($date){
    $client_timezone = request()->client_timezone;
    return \Carbon\Carbon::parse($date)->setTimezone($client_timezone)->format("Y-m-d H:i:s"); 
}

function secondDiff($end_time){
    $client_timezone = request()->client_timezone;
    $time = \Carbon\Carbon::parse($end_time)->setTimezone($client_timezone); 
    return $time->diffInSeconds(null,true);
}

function diffFormatTableOfTime($date){
    $client_timezone = request()->client_timezone;

    $diffSecond = \Carbon\Carbon::now($client_timezone)->diffInSeconds($date);
 
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

function defaultImage($module,$data = null){
    switch ($module) {
        case 'user':
            return [
                "file_path" => $data && $data->image_profile_path ? $data->image_profile_path : 'assets/default',
                "file_name" => $data && $data->image_profile_file ? $data->image_profile_file : 'user-default-icon.png',
                "file_mime" => 'image/png',
                "file_full_path" => $data && $data->image_profile_path && $data->image_profile_file ? getPublicFile($data->image_profile_path,$data->image_profile_file) : "https://storage.googleapis.com/staging_lets_flip/live/assets/user-default-icon.png"
            ];
            break;
        
        default:
            # code...
            break;
    }
}

function getPublicFile($path,$file){
    // return  Illuminate\Support\Facades\Storage::disk('gcs')->url($path.'/'.$file);
    return 'https://storage.googleapis.com/'.env('GOOGLE_CLOUD_STORAGE_BUCKET').'/'.env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX').'/'.$path.'/'.$file;
}

function subsType($type){

    $env = request()->header('environment','production');
    $add = "";

    if( $env == "staging") $add = " (Staging)";

    switch ($type) {
        case 1:
            return (object) [
                "id" => $type,
                "name" => "Basic".$add
            ];
            break;
        case 2:
            return (object) [
                "id" => $type,
                "name" => "Private Classroom".$add
            ];
            break;
        case 3:
            return (object) [
                "id" => $type,
                "name" => "Master Classroom".$add
            ];
            break;
        default:
            return (object) [
                "id" => $type,
                "name" => "Undifined".$add
            ];
            break;
    }
}


function getEmothName(){
    $emoth = config('static_db.review.emotion_list');
    return array_column($emoth,'code');
}
 
function statusRequestName($id){
    $name = 'N/A';
    switch($id){
        case 1:
            $name = "Allowed";
        break;
        case 2:
            $name = "Request";
        break;
        case 3:
            $name = "Rejected";
        break; 
        default:
            $name = "Untitled";
    }

    return $name;
}


function sort_array_of_array($array, $subfield)
{
    $sortarray = array();
    foreach ($array as $key => $row)
    {
        $sortarray[$key] = $row;
    }
    array_multisort($sortarray, SORT_ASC, $array);
    return  $sortarray;
}

function emoth_point($point){
    $val = $point;
    switch($point){
        case 100:
            $val = "💯";
        break;
    }

    return $val;
}