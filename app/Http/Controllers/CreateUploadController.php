<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class CreateUploadController extends Controller
{
    public function __invoke(): BaseResponse
    {
        $validator = Validator::make(Request::all(), [
            'file' => ['required', File::types('text/csv')],
        ]);
        View::share('errors', $validator->errors());

        if ($validator->fails()) {
            throw new ValidationException(
                $validator,
                Response::view('upload.form')->setStatusCode(
                    BaseResponse::HTTP_UNPROCESSABLE_ENTITY,
                ),
            );
        }

        /** @var UploadedFile $file */
        $file = $validator->getValue('file');
        $stream = fopen($file->path(), 'r');
        $data = [];
        while (($row = fgetcsv($stream)) !== false) {
            $data[] = $row;
        }
        fclose($stream);
        Session::put('data', $data);

        return redirect('headers')->setStatusCode(BaseResponse::HTTP_SEE_OTHER);
    }
}
