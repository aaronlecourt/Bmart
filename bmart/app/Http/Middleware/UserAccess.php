<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $isVendor)
    {
        // if(auth()->user()->isVendor == $isVendor){
        //     return $next($request);
        // }
        // return response()->redirectToRoute('vendor.home');

        // if (auth()->check() && auth()->user()->isVendor == 1) {
        //     return redirect()->route('vendor.home');
        // }

        // return $next($request);

        if($isVendor == 'buyer' && auth()->user()->isVendor == 0) {
            return redirect()->route('home')->with('error', 'You are not authorized to access this page.');
        } else if($isVendor == 'vendor' && auth()->user()->isVendor == 1) {
            return redirect()->route('vendor.home')->with('error', 'You are not authorized to access this page.');
        }
        return $next($request);
    }
}
