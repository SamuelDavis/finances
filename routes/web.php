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
use App\Http\Controllers\DeleteUploadController;
use App\Http\Controllers\ReadHeadersController;
use App\Http\Controllers\ReadTableController;
use App\Http\Middleware\HxTransformer;
use App\Http\Middleware\RequiresSessionData;
use App\Http\Middleware\SessionHasOrderedData;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn () => redirect(route('upload')))->name('home');

// Upload
Route::get('/upload', fn () => view('upload.page'))
    ->name('upload')
    ->middleware([HxTransformer::class]);
Route::post('/upload', CreateUploadController::class)
    ->name('upload')
    ->middleware([HxTransformer::class]);
Route::delete('/upload', DeleteUploadController::class)
    ->name('upload')
    ->middleware([HxTransformer::class]);

// Headers
Route::get('/headers', ReadHeadersController::class)
    ->name('headers')
    ->middleware([HxTransformer::class,
        RequiresSessionData::class,
        SessionHasOrderedData::class]);
Route::post('/headers', CreateHeadersController::class)
    ->name('headers')
    ->middleware([
        HxTransformer::class,
        RequiresSessionData::class,
        SessionHasOrderedData::class,
    ]);

// Table
Route::get('/table', ReadTableController::class)
    ->name('table')
    ->middleware([RequiresSessionData::class]);
