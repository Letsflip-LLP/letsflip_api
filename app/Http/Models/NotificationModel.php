<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationModel extends Model
{
    use HasFactory;
    use SoftDeletes; 

    public $incrementing = false; 
    protected $table = 'notifications'; 
    protected $fillable = [
        "id", "classroom_id", "point_id", "classroom_access_id", "mission_id", "respone_id", "mission_comment_id", "respone_comment_id", "user_id_from", "user_id_to", "type", "read_at", "created_at", "updated_at", "deleted_at"
    ];

    public function UserFrom()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id_from');
    }

    public function UserTo()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id_to');
    }

    public function Respone()
    {
        return $this->hasOne('App\Http\Models\MissionResponeModel','id','respone_id');
    }

    public function Mission()
    {
        return $this->hasOne('App\Http\Models\MissionModel','id','mission_id');
    }

    public function Point()
    {
        return $this->hasOne('App\Http\Models\UserPointsModel','id','point_id');
    }

    public function ClassroomAccess()
    {
        return $this->hasOne('App\Http\Models\ClassroomAccessModel','id','classroom_access_id');
    }

    public function ClassRoom()
    {
        return $this->hasOne('App\Http\Models\ClassRoomModel','id','classroom_id');
    }
}
