<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MissionModel extends Model
{
    use HasFactory;
    
    public $incrementing = false; 
    protected $table = 'missions';   

    use SoftDeletes; 

    public function MissionContent()
    {
        return $this->hasMany('App\Http\Models\MissionContentModel','mission_id','id');
    }

    public function MissionContentDefault()
    {
        return $this->hasOne('App\Http\Models\MissionContentModel','id','default_content_id');
    }

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }

    public function Like()
    {
        return $this->hasMany('App\Http\Models\LikeModel','mission_id','id');
    }

    public function Comment()
    {
        return $this->hasMany('App\Http\Models\MissionCommentModel','mission_id','id');
    }

    public function Report()
    {
        return $this->hasMany('App\Http\Models\MissionReportModel','mission_id','id');
    }

    public function Respone()
    {
        return $this->hasMany('App\Http\Models\MissionResponeModel','mission_id','id');
    }

    public function LastRespone()
    { 
        return $this->hasMany('App\Http\Models\MissionResponeModel','mission_id','id')
                ->where('created_at','<=',Carbon::now()->format('Y-m-d'))
                ->where('created_at','>=',Carbon::now()->subDays(7)->format('Y-m-d'));
    }

    public function ClassRoomTag()
    {
         return $this->belongsToMany('App\Http\Models\ClassRoomModel','tags','module_id','foreign_id')->where('tags.type',1)->where('module','mission')->withPivot(['status','id']);
    }

    public function UserTag()
    {
        return $this->belongsToMany('App\Http\Models\User','tags','module_id','foreign_id')->where('tags.type',2)->where('module','mission');
    }

    public function QuickScores()
    {
        return $this->hasMany('App\Http\Models\MissionQuestionModel','mission_id','id');
    }

}
