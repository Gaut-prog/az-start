<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginUserFromAZToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //If the user is not already login
        if(!Auth::check()){
            //If there has an az token in the url, grant access to the user
            if($token = $request->query('az_token')){
                $data = explode('//', $token);

                if(count($data) == 5){
                    $user = User::with('customer')->where('status', 1)->find($data[1]);

                    if($user && $user->customer){
                        $expected = $user->id_credential.'//'.md5($user->userN).sha1('minou');
                        $received = $data[1].'//'. $data[2];
                        
                        if($expected === $received){
                            //If the user is linked to some company, login him into that company account
                            if($data[3] != "webbuyer"){
                                $company_id = intval($data[3]);
                                //If the user is a company
                                if($user->customer_number === $company_id){
                                    $user->company_account_connected = [
                                        'id' => $user->customer_number,
                                        'role' => "webcompany"
                                    ];
                                }else{
                                    $linked_companies = $user->linked_companies;
                                    //Else check if the user still active in the given company
                                    if(key_exists($company_id, $linked_companies)){
                                        $user->company_account_connected = [
                                                'id' => $linked_companies[$company_id]->Customers_Numbers,
                                                'role' => $linked_companies[$company_id]->role
                                            ];
                                    }
                                }
                            }
                            //login now the user
                            Auth::login($user);
                        }
                    }
                }
            }  
        }
        return $next($request);
    }
}