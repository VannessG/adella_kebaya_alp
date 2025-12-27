<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchSelected{
    public function handle(Request $request, Closure $next): Response{
        // Route yang boleh diakses tanpa pilih cabang
        $excludedRoutes = [
            'select.branch', 
            'branch.select',
            'login',
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'verification.notice',
            'verification.verify',
            'verification.send',
            'logout'
        ];
        
        foreach ($excludedRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }
        
        // Jika belum memilih cabang, redirect ke halaman pilih cabang
        if (!session()->has('selected_branch')) {
            return redirect()->route('select.branch');
        }
        return $next($request);
    }
}