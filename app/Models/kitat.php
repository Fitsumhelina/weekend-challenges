<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;



class kitat extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['amount', 'description', 'interest', 'created_by'];


    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
