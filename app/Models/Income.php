<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Income extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'title',
        'source',
        'description',
        'amount',
        'status',
        'date',
        'debt',
        'created_by',
        'updated_by'
    ];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source');
    }
}
