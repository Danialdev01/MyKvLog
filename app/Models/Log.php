<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'log_day', 'log_location', 'log_place', 'log_date', 'log_section', 'log_summary', 'log_knowledge', 'log_tools', 'log_note', 'log_updated_at', 'log_created_at', 'log_status'];

    protected $casts = [
        'log_day' => 'integer',
        'log_date' => 'date',
        'log_updated_at' => 'datetime',
        'log_created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function references()
    {
        return $this->hasMany(Reference::class, 'log_id', 'log_id');
    }
}