<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MissionResponeModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ClassRoomModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $incrementing = false;
    protected $table = 'classrooms';

    public function Mission()
    {
        return $this->belongsToMany('App\Http\Models\MissionModel','tags','foreign_id','module_id')->where('tags.type',1)->where('tags.status',1); // module id = classroom , foreign_id = mission id
    }

    public function Tag()
    {
        return $this->hasMany('App\Http\Models\TagModel','foreign_id','id')->where('tags.type',1); // module id = classroom , foreign_id = mission id
    }

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }

    public function Like()
    {
        return $this->hasMany('App\Http\Models\LikeModel','classroom_id','id');
    }

    public function PremiumUserAccess()
    {
        return $this->hasMany('App\Http\Models\ClassroomAccessModel','classroom_id','id');
    }


    public function LastMission()
    {
        return $this->belongsToMany('App\Http\Models\MissionModel','tags','foreign_id','module_id')->where('tags.type',1)->where('tags.status',1)
        ->where('missions.created_at','<=',Carbon::now()->format('Y-m-d'))
        ->where('missions.created_at','>=',Carbon::now()->subDays(15)->format('Y-m-d'));
    }

    public function PriceTemplate()
    {
        return $this->hasOne('App\Http\Models\PriceTemplateModel','id','price_template_id');
    }

    public function Report()
    {
        return $this->hasMany('App\Http\Models\MissionReportModel', 'classroom_id', 'id');
    }
}
