<?php

namespace App\Http\Middleware;

use App\Header;
use App\Http\Controllers\InteractsWithSessionData;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionHasOrderedData
{
    use InteractsWithSessionData;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = $this->getSessionData();
        $givenHeaders = $data ? reset($data) : [];
        $knownHeaders = Header::names();
        $isOrdered =
            is_array($data) &&
            empty(array_diff($givenHeaders, $knownHeaders)) &&
            empty(array_diff($knownHeaders, $givenHeaders));

        return $isOrdered
            ? redirect('table', Response::HTTP_SEE_OTHER)
            : $next($request);
    }
}
