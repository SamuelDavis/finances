<?php

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

use App\Http\Controllers\CreateHeadersController;
use App\Http\Controllers\CreateUploadController;
use App\Http\Controllers\ReadHeadersController;
use App\Http\Controllers\ReadTableController;
use App\Http\Middleware\HxTransformer;
use App\Http\Middleware\SessionHasOrderedData;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Home
Route::get("/", fn() => redirect(route("upload")))->name("home");

// Upload
Route::get("/upload", fn() => view("upload.page"))
    ->name("upload")
    ->middleware([HxTransformer::class]);
Route::post("/upload", CreateUploadController::class)
    ->name("upload")
    ->middleware([HxTransformer::class]);
Route::delete("/upload", function () {
    Session::remove("data");
    return redirect(route("upload"));
})
    ->name("upload")
    ->middleware([HxTransformer::class]);

// Headers
Route::get("/headers", ReadHeadersController::class)
    ->name("headers")
    ->middleware([HxTransformer::class, SessionHasOrderedData::class]);
Route::post("/headers", CreateHeadersController::class)
    ->name("headers")
    ->middleware([HxTransformer::class, SessionHasOrderedData::class]);

// Table
Route::get("/table", ReadTableController::class)->name("table");
