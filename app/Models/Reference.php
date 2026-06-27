<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    protected $table = 'references';
    protected $primaryKey = 'reference_id';
    public $timestamps = false;

    protected $fillable = ['log_id', 'reference_file', 'reference_image', 'reference_diagram', 'reference_created_at', 'reference_status'];

    protected $casts = [
        'reference_created_at' => 'datetime',
    ];

    public function log()
    {
        return $this->belongsTo(Log::class, 'log_id', 'log_id');
    }
}