<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;



class Kitat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['amount', 'description', 'interest_rate', 'created_by'];


    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
