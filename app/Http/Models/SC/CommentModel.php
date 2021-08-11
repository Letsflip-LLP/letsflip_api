<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'comments';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo('App\Http\Models\SC\PostModel', 'post_id', 'id');
    }

    public function content()
    {
        return $this->hasMany('App\Http\Models\SC\PostContentModel', 'relation_id', 'id')->where('type', 2);
    }

    public function replies()
    {
        return $this->hasMany('App\Http\Models\SC\CommentModel', 'parent_id', 'id')->withTrashed();
    }
}