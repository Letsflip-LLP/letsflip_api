<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class MissionModel extends Model
{
    use HasFactory;
    
    public $incrementing = false; 
    protected $table = 'missions';  



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
        return $this->hasOne('App\Http\Models\LikeModel','mission_id','id');
    }

    public function Comment()
    {
        return $this->hasMany('App\Http\Models\MissionCommentModel','mission_id','id');
    }

}
