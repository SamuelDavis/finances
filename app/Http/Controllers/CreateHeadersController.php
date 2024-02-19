<?php

namespace App\Http\Controllers;

use App\Header;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class CreateHeadersController extends Controller
{
    public function __invoke(ReadHeadersController $read): BaseResponse
    {
        $data = Session::get('data');
        $validator = Validator::make(Request::all(), [
            'headers.*' => ['required', 'distinct', new In(Header::names())],
        ])->setAttributeNames(
            array_reduce(
                Header::names(),
                fn (array $acc, string $name) => ["headers.$name" => $name] +
                    $acc,
                [],
            ),
        );

        if ($validator->fails()) {
            View::share('errors', $validator->errors());
            Request::flash();
            $headers = $data[0] ?? [];
            throw new ValidationException(
                $validator,
                Response::view(
                    'headers.form',
                    compact('headers'),
                    BaseResponse::HTTP_UNPROCESSABLE_ENTITY,
                ),
            );
        }

        Session::put(
            'data',
            $this->mapDataToHeaders($validator->getValue('headers'), $data),
        );

        return redirect('table', BaseResponse::HTTP_SEE_OTHER);
    }

    private function mapDataToHeaders(array $inputHeaders, array $data): array
    {
        $knownHeaders = Header::names();
        $givenHeaders = array_shift($data);
        $ordered = [];

        foreach ($knownHeaders as $name) {
            $ordered[] = array_search($inputHeaders[$name], $givenHeaders);
        }

        $data = array_map(function (array $row) use ($ordered) {
            return array_map(function (int $index) use ($row) {
                return $row[$index];
            }, $ordered);
        }, $data);

        array_unshift($data, $knownHeaders);

        return $data;
    }
}
