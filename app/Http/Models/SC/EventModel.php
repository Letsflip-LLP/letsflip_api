<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'events';
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }
  
}
