<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ReadTableController extends Controller
{
    use InteractsWithSessionData;

    public function __invoke(): BaseResponse
    {
        $rows = $this->getSessionData();
        if (empty($rows)) {
            return redirect('upload');
        }

        $headers = array_shift($rows);

        return Response::view('table.page', compact('headers', 'rows'));
    }
    //
}
