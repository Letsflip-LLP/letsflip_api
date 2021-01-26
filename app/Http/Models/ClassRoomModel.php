<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MissionResponeModel;

class ClassRoomModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'classrooms';

    public function Mission()
    {
        return $this->belongsToMany('App\Http\Models\MissionModel','tags','foreign_id','module_id')->where('tags.type',1); // module id = classroom , foreign_id = mission id
    }

    public function Tag()
    {
        return $this->hasMany('App\Http\Models\TagModel','foreign_id','id')->where('tags.type',1); // module id = classroom , foreign_id = mission id
    }

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }
}
