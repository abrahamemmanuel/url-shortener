<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'url.validate'], function () {
    Route::post('/encode', [UrlShortenerController::class, 'encode']);
    Route::post('/decode', [UrlShortenerController::class, 'decode']);
});

Route::get('/{url_path}', [UrlShortenerController::class, 'redirect']);
Route::get('/statistic/{url_path}', [UrlShortenerController::class, 'stats']);