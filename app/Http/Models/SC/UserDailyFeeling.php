<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDailyFeeling extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'user_daily_feelings';
    protected $guarded = []; 
}
