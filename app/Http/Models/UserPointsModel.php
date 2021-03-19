<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPointsModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false; 
    protected $table = 'user_points';
    
    protected $fillable = [
        "id", "value", "classroom_id", "mission_id", "respone_id", "user_id_from", "user_id_to", "mission_comment_id", "respone_comment_id", "type", "read_at", "created_at", "updated_at", "deleted_at"
    ];
    
    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    } 
}
