<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionResponeContentModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'mission_response_contents';  
}
