<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('index');
});

Route::get('/clear/{pwd}', function() {
    if(request()->pwd !== 'Mis1234!') {
        return "You are not authorized to perform this action";
    }
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});
