<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class MissionQuestionModel extends Model
{
    use HasFactory;
    
    public $incrementing = false; 
    protected $table = 'mission_questions';

    use SoftDeletes; 


    public function Answer()
    {
        return $this->hasMany('App\Http\Models\MissionAnswerModel','question_id','id');
    }
    
}
