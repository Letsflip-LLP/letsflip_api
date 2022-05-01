<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomAccessModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false; 
    protected $table = 'classroom_accesses';
    protected $fillable = ['id','classroom_id','user_id','access_code','status'];

    public function ClassRoom()
    {
        return $this->hasOne('App\Http\Models\ClassRoomModel','id','classroom_id');
    }

    public function User()
    {
        return $this->hasMany('App\Http\Models\User', 'id', 'user_id');
    }
}
