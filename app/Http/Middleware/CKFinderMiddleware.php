<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CKFinderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ở đây có thể check auth/role admin, ví dụ:
        // if (!auth()->check() || !auth()->user()->hasRole('admin')) abort(403);

        // Demo: cho phép luôn (tự chỉnh theo nhu cầu)
        config(['ckfinder.authentication' => function () {
            return true;
        }]);
        
        return $next($request);
    }
}
