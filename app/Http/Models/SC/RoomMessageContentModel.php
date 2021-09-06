<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomMessageContentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_channel_message_content';
    protected $guarded = [];

    public function message()
    {
        return $this->belongsTo('App\Http\Models\RoomMessageModel', 'room_channel_message_id', 'id');
    }
}
