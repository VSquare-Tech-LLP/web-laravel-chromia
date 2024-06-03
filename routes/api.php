<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//** Depricated */
// Route::post('/face-swap', [FaceSwapController::class, 'uploadImages']);
// Route::post('/face-swap-results', [FaceSwapController::class, 'getResult']);
Route::post('/face-swap', [FaceSwapController::class, 'goApiFaceSwap']);
Route::post('/face-swap-results', [FaceSwapController::class, 'goApiFaceSwapResults']);

//** Not in use so closed. */
// Route::post('/face-swap-batch', [FaceSwapController::class, 'uploadImageBatch']);
// Route::get('/face-swap-batch-results/{taskId}', [FaceSwapController::class, 'getBatchResult']);

Route::post('/face-swap-pack', [FaceSwapController::class, 'goApiFaceSwapPack']);
Route::get('/face-swap-pack-results/{taskId}', [FaceSwapController::class, 'goApiFaceSwapPackResult']);
// Route::post('/face-swap-pack', [FaceSwapController::class, 'uploadImagePack']);
// Route::get('/face-swap-pack-results/{taskId}', [FaceSwapController::class, 'getBatchResult']);

Route::get('/get-categories', [AppApiController::class, 'categories']);
Route::get('/get-packs/{category?}', [AppApiController::class, 'packs']);
Route::get('/get-pack-images/{pack?}', [AppApiController::class, 'packImages']);
Route::get('/get-random-images', [AppApiController::class, 'getRandomImages']);
