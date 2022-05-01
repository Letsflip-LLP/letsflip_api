<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionReportModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'content_reports';


    public function User()
    {
        return $this->hasOne('App\Http\Models\User', 'id', 'user_id');
    }

    public function Mission()
    {
        return $this->hasOne('App\Http\Models\MissionModel', 'id', 'mission_id');
    }

    public function Classroom()
    {
        return $this->hasOne('App\Http\Models\ClassroomModel', 'id', 'classroom_id');
    }

    public function Response()
    {
        return $this->hasOne('App\Http\Models\MissionResponeModel', 'id', 'mission_respone_id');
    }
}
