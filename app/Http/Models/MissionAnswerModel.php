<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionAnswerModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'mission_answers';
 
}
