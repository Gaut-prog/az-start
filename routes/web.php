<?php

use App\Http\Controllers\Logout;
use App\Http\Controllers\SwitchCompany;
use App\Http\Middleware\SetDatabase;
use Illuminate\Support\Facades\Route;

//The home page showing directly the login form
Route::livewire('/', 'pages::home')->name('login')->middleware('guest');

Route::middleware('auth')->group(function(){
    Route::get('auth/{id}/switch-company', SwitchCompany::class)->name('switch_company');
    Route::get('logout', Logout::class)->name('logout');

    //This middleware will load the company’s database dynamically
    //Put all your routes needed access to the database here
    Route::middleware([SetDatabase::class])->group(function(){
        Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    });
});