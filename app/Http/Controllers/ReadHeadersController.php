<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ReadHeadersController extends Controller
{
    public function __invoke(): BaseResponse
    {
        $headers = Session::get('data')[0] ?? [];

        return Response::view('headers.page', compact('headers'));
    }
}
