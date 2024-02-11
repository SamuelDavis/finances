<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ReadTableController extends Controller
{
    public function __invoke(): BaseResponse
    {
        if (!Session::has("data")) {
            return redirect("upload");
        }

        $rows = Session::get("data");
        $headers = array_shift($rows);
        return Response::view("table.page", compact("headers", "rows"));
    }
    //
}
