<?php

use App\Domains\Flux\Http\Controllers\AppController;
use App\Domains\Flux\Http\Controllers\FluxController;
use App\Domains\Flux\Http\Controllers\GoApiFluxController;
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


Route::post('/home', [AppController::class, 'home']);
if (env('USE_API', "replicate") == "replicate") {
  Route::post('/generate', [FluxController::class, 'generate']);
  Route::post('/get-results', [FluxController::class, 'getresults']);
}elseif(env('USE_API', "replicate") == 'goapi') {
  Route::post('/generate', [GoApiFluxController::class, 'generate']);
  Route::post('/get-results', [GoApiFluxController::class, 'getresults']);
}else{
  Route::post('/generate', [FluxController::class, 'generate']);
  Route::post('/get-results', [FluxController::class, 'getresults']);
}
Route::post('/generate-test', [FluxController::class, 'generate']);
Route::post('/get-results-test', [FluxController::class, 'getresults']);
