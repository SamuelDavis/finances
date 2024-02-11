<?php

namespace App\Http\Middleware;

use App\Header;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionHasOrderedData
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = Session::get("data");
        $givenHeaders = reset($data);
        $knownHeaders = Header::names();
        $isOrdered =
            is_array($data) &&
            empty(array_diff($givenHeaders, $knownHeaders)) &&
            empty(array_diff($knownHeaders, $givenHeaders));

        Log::debug("isOrdered", [$isOrdered]);

        return $isOrdered
            ? redirect("table", Response::HTTP_SEE_OTHER)
            : $next($request);
    }
}
