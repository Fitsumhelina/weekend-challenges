<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        'title',
        'description',
        'service_list',
        'price',
        'icon',
    ];

    protected $casts = [
        'service_list' => 'array',
    ];

    // Define any relationships or additional methods if needed
}
