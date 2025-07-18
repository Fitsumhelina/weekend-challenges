<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'amount',
        'created_By',
        'updated_By'
        
    ];
    
}
