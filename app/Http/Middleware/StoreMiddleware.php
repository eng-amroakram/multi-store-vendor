<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->user_type == 'store_admin' || auth()->user()->user_type == 'store_employee')
        {
            return $next($request);
        }

        return redirect()->route('admin.home');
    }
}
