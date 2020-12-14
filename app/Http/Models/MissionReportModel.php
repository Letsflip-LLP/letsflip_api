<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionReportModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'content_reports';


    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }
}
