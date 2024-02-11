<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HxTransformer
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (!$request->header("Hx-Request")) {
            return $response;
        }

        if ($location = $response->headers->get("Location")) {
            $response->headers->set("Hx-Redirect", $location);
            $response->headers->remove("Location");
        }
        return $response;
    }
}
