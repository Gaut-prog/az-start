<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Logout extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        Cookie::queue(Cookie::forget('company_account_connected'));
        Auth::logout();
        request()->session()->invalidate();
    
        request()->session()->regenerateToken();
        
        return to_route('login')->with('notification', [
                'type' => 'success',
                'title' => __('auth.logout'),
            ]);
    }
}