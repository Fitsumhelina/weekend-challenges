<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'category',
        'description',
        'amount',
        'date',
        'created_by',
        'updated_by'
    ];

    public function createdByUser()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

}
