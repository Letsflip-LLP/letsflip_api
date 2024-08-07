<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class MissionResponeModel extends Model
{
    use HasFactory;
    use SoftDeletes; 

    public $incrementing = false; 
    protected $table = 'mission_responses';  

    protected $fillable = [
        "id", "user_id", "mission_id", "title", "text", "default_content_id", "image_path", "image_file", "status", "type", "created_at", "updated_at", "deleted_at"
    ];   

    public function MissionContent()
    {
        return $this->hasMany('App\Http\Models\MissionResponeContentModel','mission_respone_id','id');
    }

    public function MissionContentDefault()
    {
        return $this->hasOne('App\Http\Models\MissionResponeContentModel','id','default_content_id')->where('type',1);
    }

    public function CollaborationContent()
    {
        return $this->hasOne('App\Http\Models\MissionResponeContentModel','mission_response_id','id')->where('type',2);
    }

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }

    public function Like()
    {
        return $this->hasMany('App\Http\Models\LikeModel','mission_respone_id','id');
    }

    public function Comment()
    {
        return $this->hasMany('App\Http\Models\MissionCommentResponeModel','mission_respone_id','id');
    }

    public function Report()
    {
        return $this->hasMany('App\Http\Models\MissionReportModel','mission_respone_id','id');
    }

    public function Mission()
    {
        return $this->hasOne('App\Http\Models\MissionModel','id','mission_id');
    }

    public function Point()
    {
        return $this->hasOne('App\Http\Models\UserPointsModel','respone_id','id')->where('type',5);
    }

    public function GradeOverview(){
        return $this->hasOne('App\Http\Models\GradeOverviewModel','mission_response_id','id');
    }

    public function ClassRoomTag()
    {
         return $this->belongsToMany('App\Http\Models\ClassRoomModel','tags','module_id','foreign_id')->where('tags.type',1)->where('module','response');
    }

    public function UserTag()
    {
        return $this->belongsToMany('App\Http\Models\User','tags','module_id','foreign_id')->where('tags.type',2)->where('module','response');
    }

}
