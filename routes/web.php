<?php

declare(strict_types=1);

use App\Http\Controllers\UrlCheckController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UrlController::class, 'create'])->name('index');
Route::resource('urls', UrlController::class);

Route::resource('urls.checks', UrlCheckController::class);
