<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\MissionResponeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $incrementing = false;
    protected $table = 'companies';
 
    public function Users()
    {
        return $this->hasMany('App\Http\Models\User','company_id','id');
    }

}
