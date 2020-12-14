<?php namespace App\Http\Libraries\StorageCdn;
 
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use Ramsey\Uuid\Uuid;

class StorageManager {
 
    public function __construct(){
        
    }

    public function uploadFile($path,$file){
        try {
            $m = $file->getMimeType();
            $mime = explode("/",$m)[0];
            $extention = explode("/",$m)[1];

            $filename = Uuid::uuid4().'.'.$extention;

            // $filename

            if($mime == 'image') $upload = $this->_resizeMultiUpload($path.'/'.$mime,$file,$mime,$filename);
            
            if($mime == 'video') $upload = $this->_videoWithThumUpload($path.'/'.$mime,$file,$mime,$filename);


            return $upload;

        }catch(Exception $e) {
            return $e; 
        } 
    }  

    private function _videoWithThumUpload($path,$file,$mime,$filename){ 
        $mime = $file->getMimeType();

        $save = Storage::disk('gcs')->putFileAs( $path , $file , $filename , 'public'); 

        if($save)
            return (object) [
                "file_path" =>  $path,
                "file_name" =>  $filename,
                "file_mime" =>  $mime
            ];
    }

    private function _resizeMultiUpload($path,$file,$mime,$filename){
        $originalSize   = getimagesize($file);
        $width = $originalSize[0];
        $height = $originalSize[1];
        $ratio  = 2; 

        try {
            $thumbnail = static::autoRatio($width,$height);
            $tempPath = 'tmp';
            $deleteTemp = [];
            Storage::disk('gcs')->putFileAs($path, $file ,$filename,'public');

            foreach ($thumbnail as $key => $size) {
                $newPath = $tempPath.'/'.$key;
                $savePath = $tempPath.'/'.$key.'/'.$filename; 

                $save_tmp = \Image::make($file)->resize($size['width'], $size['height'])->save($savePath);

                $save_tmp_path = new \SplFileInfo($save_tmp->dirname.'/'.$save_tmp->filename.'.'.$save_tmp->extension);

                array_push($deleteTemp,$savePath);
                    
                $save = Storage::disk('gcs')->putFileAs($path.'/'.$key, $save_tmp_path , $filename, "public");
            }
            
            
            foreach($deleteTemp as $value){
                try { 
                    if(!is_writable($value)){
                        alert()->warning('Permission denied delete file!');
                        return back();
                    }
                    unlink($value);
                }
                catch(Exception $e) {
                    alert()->warning('Permission denied delete file!');
                    return back();
                }

            }

           return (object) [
               "file_path" =>  $path,
               "file_name" =>  $filename,
               "file_mime" =>  $mime
           ];

        }catch(Exception $e) {
            alert()->warning('Error');
            return back();
        }
    }

    private static function autoRatio($width,$height,$ratio=null,$autoResize=false)
    {
        $perbandingan=['xsmall'=>2,'small'=>5,'medium' => 10,'large'=>15,'xlarge'=>20];
        if($width <= 175){
            $value = 'xsmall';
        }elseif($width > 175 && $width <=375){
            $value = 'small';

        }elseif($width > 375 && $width <= 625){
            $value = 'medium';
        }
        elseif($width > 625 && $width <= 875){
            $value = 'large';
        }else{
            $value = 'xlarge';
        }
        $t = 100;
        $data = array();
        foreach($perbandingan as $name => $angka){
            if($autoResize == false){
                $data[$name]['width'] = ($perbandingan[$name]/$perbandingan['xsmall'])*$t;
            }
            else{
                $data[$name]['width'] = ($perbandingan[$name]/$perbandingan[$value])*$width;
            }
            $data[$name]['height'] = ($ratio === null ? $height/$width*$data[$name]['width'] : $data[$name]['width']/($ratio[0]/$ratio[1]));
        }
        return $data;
    }
  
}