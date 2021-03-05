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
}
