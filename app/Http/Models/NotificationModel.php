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
}
