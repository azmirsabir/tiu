<?php

namespace App\Http\Middleware;

use App\Helper;
use Closure;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class dev_admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (str_contains( json_encode(Helper::get_ldap_employee('azmir.sleman')['emp_info'][0]['memberof']), 'IT_CIM')) {
            return $next($request);
        }
        return redirect('/home');
    }
}
