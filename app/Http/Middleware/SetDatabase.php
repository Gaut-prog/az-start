<?php

namespace App\Http\Middleware;

use App\Action\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $company = auth()->user()->linked_company ?? null;
        if(!$company)
            abort(404);
        if($company['Status'] != 1)
            abort(403, __("Votre entreprise n'est pas active sur az-companies.com"));
        
        if(config('app.env') != 'local' && !$request->routeIs('dashboard')){
            if(!Helpers::isPremium($company['Customers_Numbers'])){
                Cookie::queue(Cookie::forget('company_account_connected')); 
                Auth::logout();
                return to_route('login')->with('notification', [
                        'type' => 'error',
                        'title' => __("L'abonnement de votre entreprise est expirée, veuillez le renouveler"),
                    ]);
            }
        }
        
        if(Helpers::customer_dbSet($company['customer_db'] ?? null) == false){
            abort(403, __("Base de données introuvable"));
        }
        
        return $next($request);
    }
}