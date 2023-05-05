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
//     public function handle(Request $request, Closure $next, $isVendor)
//     {
//         // if(auth()->user()->isVendor == $isVendor){
//         //     return $next($request);
//         // }
//         // return response()->redirectToRoute('vendor.home');

//         if(auth()->user()->isVendor =='buyer'){
//             return $next($request)
//             //prevent the site from cache-ing and redirects to login when user tries to go back after log out
//             ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
//             ->header('Pragma', 'no-cache')
//             ->header('Expires', '0');
//         }else if(auth()->user()->isVendor == 'vendor'){
//             return redirect()->route('vendor.home')
//             //prevent the site from cache-ing and redirects to login when user tries to go back after log out
//             ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
//             ->header('Pragma', 'no-cache')
//             ->header('Expires', '0');
//         }
//         return back();
//     }
// }
public function handle(Request $request, Closure $next, $isVendor)
{
    if(auth()->user()->isVendor == $isVendor){
        return $next($request);
    }

    if(auth()->user()->isVendor == 'buyer'){
        return redirect()->route('home')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    if(auth()->user()->isVendor == 'vendor'){
        return redirect()->route('vendor.home')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    return back();
}
}