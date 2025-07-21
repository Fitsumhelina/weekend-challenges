<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'source',
        'description',
        'amount',
        'status',
        'created_By',
        'updated_By'
    ];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_By');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_By');
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source');
    }
}
