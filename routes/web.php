<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;

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
Route::post('/bai-viet/{post}/comment', [App\Http\Controllers\Frontend\CommentController::class, 'store'])->name('comment.store');

Route::get('/lien-he', [App\Http\Controllers\Frontend\ContactController::class, 'showForm'])->name('contact');
Route::post('/lien-he', [App\Http\Controllers\Frontend\ContactController::class, 'submit'])->name('contact.submit');

Auth::routes();

// Admin routes - Chỉ dành cho users đã login và có role
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard - Tất cả users đã login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories CRUD
    Route::resource('categories', CategoryController::class);

    // Posts
    Route::resource('posts', PostController::class);

    Route::resource('comments', CommentController::class)->except('create','store','edit','update','show');
    Route::post('comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');

    Route::resource('contacts', ContactRequestController::class)->only(['index','show','destroy']);

    Route::resource('menus', MenuController::class);
    Route::resource('menu-items', MenuItemController::class);

    // Setting
    Route::resource('settings', SettingController::class)->only([
        'index', 'edit', 'update'
    ]);
    
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
