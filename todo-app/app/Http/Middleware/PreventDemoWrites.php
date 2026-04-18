<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoWrites
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.demo_read_only') && ! $request->isMethodSafe()) {
            abort(Response::HTTP_FORBIDDEN, 'The live demo is read-only.');
        }

        return $next($request);
    }
}
