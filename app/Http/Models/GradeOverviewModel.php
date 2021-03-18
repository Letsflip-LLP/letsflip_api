<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MissionResponeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeOverviewModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $incrementing = false;
    protected $table = 'grade_overviews'; 
    protected $fillable = [
        "id", "mission_response_id", "text", "quality", "creativity", "language", "point", "created_at", "updated_at", "deleted_at"
    ]; 

}
