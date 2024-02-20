<?php

namespace App\Http\Controllers;

use App\Header;
use App\Rules\KeyIn;
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
    use InteractsWithSessionData;

    public function __invoke(ReadHeadersController $read): BaseResponse
    {
        $data = $this->getSessionData();
        $validator = Validator::make(Request::all(), [
            'headers' => ['required', 'array', new KeyIn($data[0])],
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

        $headers = $validator->getValue('headers');
        assert(is_array($headers));
        Session::put('data', $this->mapDataToHeaders($headers, $data));

        return redirect('table', BaseResponse::HTTP_SEE_OTHER);
    }

    /**
     * @param  string[]  $inputHeaders
     * @param  string[][]  $data
     * @return string[][]
     */
    private function mapDataToHeaders(array $inputHeaders, array $data): array
    {
        $knownHeaders = Header::names();
        $givenHeaders = array_shift($data);
        assert(! empty($givenHeaders));
        $ordered = [];

        foreach ($knownHeaders as $name) {
            $header = array_search($name, $inputHeaders);
            $index = array_search($header, $givenHeaders);
            assert(is_int($index));
            $ordered[] = $index;
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
