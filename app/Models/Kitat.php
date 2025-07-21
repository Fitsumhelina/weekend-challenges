<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Kitat extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'description', 'interest', 'created_by'];


    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
