<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->isApproved()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'บัญชีของคุณยังไม่ได้รับการอนุมัติ');
        }

        return $next($request);
    }
}