<?php

use App\Http\Controllers\Backend\AppBackendController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ImageController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });


//====== Robots.txt route ===========//
Route::get('robots-file-read', [DashboardController::class, 'robotsFileRead'])->name('robots_file_read');
Route::post('robots-file-write', [DashboardController::class, 'robotsFileWrite'])->name('robots_file_write');

Route::get('categories', [AppBackendController::class, 'showaCategories'])->name('categories.index');
Route::get('categories/{category}/edit', [AppBackendController::class, 'editCategory'])->name('categories.edit');
Route::post('categories/store', [AppBackendController::class, 'storeCategory'])->name('categories.store');
Route::get('packs', [AppBackendController::class, 'showPacks'])->name('packs');
Route::get('photos', [AppBackendController::class, 'showPhotos'])->name('photos');
Route::get('swaplogs', [AppBackendController::class, 'swapLogs'])->name('swaplogs');


Route::get('category/delete/{id}', [AppBackendController::class, 'deleteCat'])->name('category.delete');



Route::resource('images', ImageController::class);

