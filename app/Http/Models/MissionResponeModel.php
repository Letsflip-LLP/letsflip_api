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



    public function MissionContent()
    {
        return $this->hasMany('App\Http\Models\MissionResponeContentModel','mission_respone_id','id');
    }

    public function MissionContentDefault()
    {
        return $this->hasOne('App\Http\Models\MissionResponeContentModel','id','default_content_id');
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

}
