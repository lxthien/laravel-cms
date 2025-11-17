<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;

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

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/tim-kiem', [App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search');
Route::get('/bai-viet/{slug}', [App\Http\Controllers\Frontend\PostController::class, 'show'])->name('post.show');
Route::get('/danh-muc/{slug}', [App\Http\Controllers\Frontend\CategoryController::class, 'show'])->name('category.show');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin routes - Chỉ dành cho users đã login và có role
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard - Tất cả users đã login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories CRUD
    Route::resource('categories', CategoryController::class);

    // Posts
    Route::resource('posts', PostController::class);
    
    // User Management - Chỉ admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
    
    // Post Management - Admin, Editor, Author
    Route::middleware(['role:admin|editor|author'])->group(function () {
        Route::resource('posts', PostController::class);
    });
    
    // Category Management - Admin, Editor
    Route::middleware(['role:admin|editor'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });
    
    // Hoặc kiểm tra theo permission cụ thể
    Route::middleware(['permission:post-create'])->group(function () {
        Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    });
});
