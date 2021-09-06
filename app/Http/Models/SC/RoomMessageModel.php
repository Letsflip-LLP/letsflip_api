<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomMessageModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_channel_message';
    protected $guarded = [];

    public function channel()
    {
        return $this->belongsTo('App\Http\Models\SC\RoomChannelModel', 'channel_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function content()
    {
        return $this->hasMany('App\Http\Models\SC\RoomMessageModel', 'room_channel_message_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\Http\Models\SC\RoomMessageModel', 'parent_id', 'id')->orderBy('created_at', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo('App\Http\Models\SC\RoomMessageModel', 'parent_id', 'id');
    }
}
