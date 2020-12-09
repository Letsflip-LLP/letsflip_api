<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class MissionModel extends Model
{
    use HasFactory;
    
    public $incrementing = false; 
    protected $table = 'missions';  
}
