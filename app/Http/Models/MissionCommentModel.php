<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionCommentModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'mission_comments';

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }

    public function Comment()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function Like()
    {
        return $this->hasMany('App\Http\Models\LikeModel','mission_comment_id','id');
    }

    public function Report()
    {
        return $this->hasMany('App\Http\Models\MissionReportModel','mission_id','id');
    }
}
