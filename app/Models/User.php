<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;  // ← this must be here

class User extends Authenticatable
{
    use HasApiTokens;  // ← this must be here

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
