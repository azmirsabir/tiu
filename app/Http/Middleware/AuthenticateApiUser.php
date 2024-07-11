<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateApiUser
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
        $auth = Auth::once(['user_name'=>$request->get('user_name'),'password'=>config('ldap_auth.ldap_auth.sys_global_pass')]);

        if (!$auth) {
            return response()
                ->json(['status'=>'failed']);
        }
        return $next($request);
    }
}
