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
        'created_By',
        'updated_By'
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_By');
    }

    public function updatedByUser()
    {
        return $this->belongsTo('App\Models\User', 'updated_By');
    }
}
