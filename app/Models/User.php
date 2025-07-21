<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles,HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

 
    public function income(){
            return $this->hasMany(Income::class);
    }

    public function expenses(){
            return $this->hasMany(Expense::class);
    }
    
    public function posts(){
            return $this->hasMany(post::class);
    }
    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
