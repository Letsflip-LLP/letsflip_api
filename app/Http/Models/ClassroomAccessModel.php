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
 
}
