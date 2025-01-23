<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected array $fillable = ['name', 'email', 'mobile', 'password', 'created_at'];
}
