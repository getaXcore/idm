<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $username = 'jt05up3r';
    private $password = 'jt00319';

    public function handle($request, Closure $next)
    {
        $AUTH_USER = $request->getUser();
        $AUTH_PASS = $request->getPassword();

        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        if (isset($AUTH_USER) && isset($AUTH_PASS)){
            if ($AUTH_USER != $this->username || $AUTH_PASS != $this->password){
                return response('Unauthorized',401,array("Content-Type"=>"text/html; charset=utf-8"));
            }
        }else{
            return response('Unauthorized',401,array("Content-Type"=>"text/html; charset=utf-8"));
        }

        return $next($request);
    }
}
