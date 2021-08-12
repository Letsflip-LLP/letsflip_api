<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFriendsModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'user_friends';
    protected $guarded = [];

    public function UserFrom()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id_from', 'id');
    }

    public function UserTo()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id_to', 'id');
    }

    public function Invitation()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id_from', 'id')->where('type', 2);
    }

    public function Invited()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id_to', 'id')->where('type', 2);
    }
}
