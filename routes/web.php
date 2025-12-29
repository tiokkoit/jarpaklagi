<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Keep original welcome page for reference
Route::get('/welcome', function () {
    return view('welcome');
});
