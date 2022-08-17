<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MissionCommentResponeModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'mission_respone_comments';

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
        return $this->hasMany('App\Http\Models\LikeModel','mission_respone_comment_id','id');
    }

    public function Report()
    {
        return $this->hasMany('App\Http\Models\MissionReportModel','mission_id','id');
    }
}
