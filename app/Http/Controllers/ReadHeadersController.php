<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ReadHeadersController extends Controller
{
    use InteractsWithSessionData;

    public function __invoke(): BaseResponse
    {
        $headers = $this->getSessionData()[0] ?? [];

        return Response::view('headers.page', compact('headers'));
    }
}
