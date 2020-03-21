<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class CheckPermisson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user()->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}
