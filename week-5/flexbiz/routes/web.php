<?php

use Illuminate\Support\Facades\Route;


use App\Models\Service;
use App\Models\Contactinfo;

Route::get('/', function () {
    $services = Service::all();
    $contactinfos = Contactinfo::all();
    return view('index', compact('services', 'contactinfos'));
});
