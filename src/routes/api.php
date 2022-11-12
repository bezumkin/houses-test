<?php

use App\Http\Controllers\Houses;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('houses', [Houses::class, 'getHouses']);

Route::get('house-filters', [Houses::class, 'getFilters']);
