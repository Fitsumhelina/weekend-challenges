<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'updated_by',
        'updated_at',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

