<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionAnswerModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'mission_answers';
    protected $fillable = [
        "id", "mission_response_id","question_id", "user_id", "answer", "index", "point", "created_at", "updated_at", "deleted_at", "is_true", "payload"
    ];


    public function Question()
    {
        return $this->hasOne('App\Http\Models\MissionQuestionModel','id','question_id');
    }

}
