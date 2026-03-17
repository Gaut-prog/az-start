<?php

use App\Http\Controllers\Logout;
use App\Http\Controllers\SwitchCompany;
use Illuminate\Support\Facades\Route;

//The home page showing directly the login form
Route::livewire('/', 'pages::home')->name('login')->middleware('guest');

Route::middleware('auth')->group(function(){
    Route::get('auth/{id}/switch-company', SwitchCompany::class)->name('switch_company');
    Route::get('logout', Logout::class)->name('logout');

    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
});