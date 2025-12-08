<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearBranchOnLogout
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Jika route adalah logout, clear branch session
        if ($request->routeIs('logout')) {
            $request->session()->forget('selected_branch');
        }
        
        return $response;
    }
}