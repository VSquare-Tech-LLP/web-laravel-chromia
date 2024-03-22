<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/face-swap', [FaceSwapController::class, 'uploadImages']);
Route::post('/face-swap-results', [FaceSwapController::class, 'getResult']);
