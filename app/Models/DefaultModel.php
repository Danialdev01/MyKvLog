<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultModel extends Model
{
    use HasFactory;

    protected $table = 'defaults';
    protected $primaryKey = 'default_id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'default_internship_period', 'default_department', 'default_location', 'default_company', 'default_job_scope', 'default_updated_at', 'default_created_at', 'default_status'];

    protected $casts = [
        'default_updated_at' => 'datetime',
        'default_created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}