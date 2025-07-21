<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = ['title', 'description', 'dept'];

    
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_By');
    }
}
