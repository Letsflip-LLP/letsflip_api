<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class MissionUserTimerModel extends Model
{
    use HasFactory;
    use SoftDeletes; 

    public $incrementing = false; 
    protected $table = 'mission_user_timers';  
    protected $fillable = [
        "id", "user_id", "mission_id", "time_start", "time_end", "timer", "created_at", "updated_at", "deleted_at"
    ]; 
}
