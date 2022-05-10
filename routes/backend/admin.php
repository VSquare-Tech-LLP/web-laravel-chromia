<?php

use App\Http\Controllers\Backend\Blog\CategoryController;
use App\Http\Controllers\Backend\Blog\PostController;
use App\Http\Controllers\Backend\Blog\TagController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\FormController;
use App\Http\Controllers\Backend\FormSubmissionController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\OptionController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\RedirectController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });

//====== Blog system routes start ===========//

// Categories route
Route::resource('categories', CategoryController::class);
// Tags route
Route::resource('tags', TagController::class);

// Posts route
Route::get('preview/{slug}',[PostController::class,'postPreview'])->name('post-preview');
Route::resource('posts', PostController::class);

//===== Pages route =========//
Route::get('preview/p/{slug}',[PageController::class,'pagePreview'])->name('page-preview');
Route::resource('pages', PageController::class);


// Comment Route
Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
Route::get('comments/status/{comment}/{status}',[CommentController::class, 'status'])->name('comments.status');
Route::get('comments/status/{comment}/{status}',[CommentController::class, 'status'])->name('comments.status');
Route::delete('comments/destroy/{comment}',[CommentController::class, 'destroy'])->name('comments.destroy');

//====== Blog system routes end ===========//


// File manager route
Route::get('file-manager', [DashboardController::class, 'getFileManager'])->name('file-manager');

// Redirects route
Route::resource('redirects', RedirectController::class);

//====== Form Routes ======//
Route::resource('forms', FormController::class)->only(['index','create','store','edit','update','destroy']);
//===== Form Submissions =====//
Route::resource('form-submission', FormSubmissionController::class)->only(['index','store','destroy']);

//Menu Manager Route
Route::get('menu-manager', [MenuController::class,'index'])->name('menu-manager');
Route::group(['middleware' => config('menu.middleware')], function () {

    Route::post('create-new-menu', [MenuController::class, 'createNewMenu'])->name('hcreateNewMenu');
    Route::post('delete-menug', [MenuController::class,'deleteMenu'])->name('hdeleteMenu');

    Route::post('add-custom-menu', [MenuController::class,'addCustomMenu'])->name('haddCustomMenu');
    Route::post('save-custom-menu', [MenuController::class,'saveCustomItem'])->name('hsaveCustomItem');
    Route::post('delete-item-menu', [MenuController::class,'deleteItemMenu'])->name('hdeleteItemMenu');
    Route::post('update-item', [MenuController::class,'updateItem'])->name('hupdateItem');
    Route::post('deletemenug', [MenuController::class,'deletemenug'])->name('hdeletemenug');
    Route::post('generate-menu-control', [MenuController::class,'generateMenuControl'])->name('hgenerateMenuControl');
    Route::post('update-menu-order', [MenuController::class,'updateMenuOrder'])->name('updateMenuOrder');
});


//====== Settings route ===========//
Route::post('settings/store', [OptionController::class,'store'])->name('settings.store');
Route::get('settings/general',[OptionController::class,'general'])->name('settings.general');
Route::get('settings/home-page',[OptionController::class,'homePage'])->name('settings.home-page');
Route::get('settings/footer',[OptionController::class,'footer'])->name('settings.footer');
Route::get('settings/scripts',[OptionController::class,'scripts'])->name('settings.scripts');
Route::get('settings/logo',[OptionController::class,'logo'])->name('settings.logo');
Route::get('settings/colors',[OptionController::class,'colors'])->name('settings.colors');
Route::get('settings/cache',[OptionController::class,'cache'])->name('settings.cache');
Route::post('settings/cache-purge',[OptionController::class,'cachePurge'])->name('settings.cache-purge');
Route::post('settings/cache-purge-all',[OptionController::class,'cachePurgeAll'])->name('settings.cache-purge-all');


//====== Robots.txt route ===========//
Route::get('robots-file-read', [DashboardController::class, 'robotsFileRead'])->name('robots_file_read');
Route::post('robots-file-write', [DashboardController::class, 'robotsFileWrite'])->name('robots_file_write');
