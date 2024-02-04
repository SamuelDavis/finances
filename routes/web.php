<?php

use App\Csv;
use App\Headers;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\In;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return view("upload");
})->name("index");

Route::post("/upload", function () {
    $input = Request::all();
    $validator = Validator::make($input, [
        "file" => ["required", File::types(["text/csv"])],
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();
        return new Response(
            view("components/form", compact("errors")),
            BaseResponse::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /** @var UploadedFile $file */
    $file = $validator->validated()["file"];
    $csv = Csv::fromUploadedFile($file);
    Session::put("current", $csv);
    return view("headers", compact("csv"));
})->name("upload");

Route::post("/headers", function () {
    /** @var Csv $csv */
    $csv = Session::get("current");
    $input = Request::all();
    Session::flashInput($input);
    $validator = Validator::make($input, [
        "headers" => ["array"],
        "headers." . Headers::Date->name => ["required"],
        "headers." . Headers::Description->name => ["required"],
        "headers." . Headers::Amount->name => ["required"],
        "headers.*" => ["distinct", new In($csv->getHeaders())],
    ])->setAttributeNames(
        array_reduce(
            Headers::cases(),
            fn(array $acc, UnitEnum $case) => $acc + [
                "headers.$case->name" => $case->name,
            ],
            [],
        ),
    );

    if ($validator->fails()) {
        $errors = $validator->errors();
        return new Response(
            view("headers", compact("errors", "csv")),
            BaseResponse::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    $csv->setHeaders($validator->getValue("headers"));
    $headers = $csv->getHeaders();
    $rows = collect($csv->getRows())->sortBy("date")->reverse()->toArray();
    return view("table", compact("headers", "rows"));
})->name("headers");
