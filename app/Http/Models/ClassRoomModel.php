<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoomModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'classrooms';
}
