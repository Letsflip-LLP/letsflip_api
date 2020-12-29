<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class PasswordResetModel extends Model
{
    use HasFactory;
    public $incrementing = false; 
    protected $table = 'password_resets'; 
    protected $fillable = [
        "email",	"token",	"created_at"
    ];
}
