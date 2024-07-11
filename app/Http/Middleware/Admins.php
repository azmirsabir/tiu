<?php

namespace App\Http\Middleware;

use App\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Admins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ldap_res=Helper::get_ldap_employee(Auth::user()->user_name);
        if($ldap_res['success']){
            $groups=$ldap_res['emp_info'][0]['memberof'];
            if (str_contains( json_encode($groups), 'Admin_Fleet_Admins') || str_contains( json_encode($groups), 'IT_CIM')) {
                return $next($request);
            }
        }
        return redirect('/home');
    }
}
