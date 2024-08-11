<?php

use App\Domains\Flux\Http\Controllers\FluxController;
use App\Http\Controllers\AppApiController;
use App\Http\Controllers\FaceSwapController;
use Illuminate\Http\Request;

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


Route::post('/generate', [FluxController::class, 'generate']);
Route::post('/get-results', [FluxController::class, 'getresults']);
