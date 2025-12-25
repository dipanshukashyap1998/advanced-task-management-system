<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/', function () {
    // Handle login logic here or redirect
return view('login');
})->name('login.post');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function () {
    // Handle register logic here or redirect
    return redirect('/');
})->name('register.post');
