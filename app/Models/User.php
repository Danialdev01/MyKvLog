<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = ['user_email', 'user_hash_password', 'user_type_login', 'user_ai_usage', 'user_loggedin_at', 'user_updated_at', 'user_created_at', 'user_status'];

    protected $hidden = ['user_hash_password', 'remember_token'];

    protected $casts = [
        'user_ai_usage' => 'integer',
        'user_loggedin_at' => 'datetime',
        'user_updated_at' => 'datetime',
        'user_created_at' => 'datetime',
    ];

    public function getAuthPasswordName(): string
    {
        return 'user_hash_password';
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id', 'user_id');
    }

    public function defaults()
    {
        return $this->hasOne(DefaultModel::class, 'user_id', 'user_id');
    }

    public function references()
    {
        return $this->hasManyThrough(Reference::class, Log::class, 'user_id', 'log_id', 'user_id', 'log_id');
    }
}