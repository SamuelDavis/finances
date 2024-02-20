<?php

namespace App\Http\Middleware;

use App\Http\Controllers\InteractsWithSessionData;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiresSessionData
{
    use InteractsWithSessionData;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return empty($this->getSessionData())
            ? redirect('upload')
            : $next($request);
    }
}
