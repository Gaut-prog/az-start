<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwitchCompany extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $user = $request->user();
        if($user){
            $company = $user->linked_companies[$id] ?? null;
            if($company){
                $user->company_account_connected = [
                    'id' => $id,
                    'role' => $company->role
                ];
              
                return to_route('dashboard')->with('notification', [
                    'type' => 'success',
                    'title' => __('auth.account_changed'),
                ]);
            }
        }
        return back();
    }
}