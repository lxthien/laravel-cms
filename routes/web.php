<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use CKSource\CKFinderBridge\Controller\CKFinderController;

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
Route::get('/api/search/suggestions', [App\Http\Controllers\Frontend\SearchController::class, 'suggestions'])->name('search.suggestions');
//Route::get('/bai-viet/{slug}', [App\Http\Controllers\Frontend\PostController::class, 'show'])->name('post.show');
//Route::get('/danh-muc/{slug}', [App\Http\Controllers\Frontend\CategoryController::class, 'show'])->name('category.show');
Route::post('/bai-viet/{post}/comment', [App\Http\Controllers\Frontend\CommentController::class, 'store'])->name('comment.store');

Route::get('/lien-he', [App\Http\Controllers\Frontend\ContactController::class, 'showForm'])->name('contact');
Route::post('/lien-he', [App\Http\Controllers\Frontend\ContactController::class, 'submit'])->name('contact.submit');

Auth::routes();

Route::group(['prefix' => 'ckfinder', 'middleware' => ['web', 'auth', 'ckfinder']], function () {
    Route::any('connector', [CKFinderController::class, 'requestAction'])
        ->name('ckfinder_connector');

    Route::any('browser', [CKFinderController::class, 'browserAction'])
        ->name('ckfinder_browser');
});

// Admin routes - Chỉ dành cho users đã login và có role
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard - Tất cả users đã login
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories CRUD
    Route::resource('categories', CategoryController::class);
    // Route mới để update order
    Route::post('categories/{category}/update-order', [CategoryController::class, 'updateOrder'])
        ->name('categories.update-order');
    // Update status (mới)
    Route::post('categories/{category}/update-status', [CategoryController::class, 'updateStatus'])
        ->name('categories.update-status');

    // Posts
    Route::resource('posts', PostController::class);
    Route::get('/posts/category/{category}', [PostController::class, 'byCategory'])
        ->name('posts.by-category');

    // Pages Management
    Route::resource('pages', PageController::class);
    Route::patch('pages/{page}/update-order', [PageController::class, 'updateOrder'])->name('pages.update-order');
    Route::patch('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

    Route::resource('comments', CommentController::class)->except('create', 'store', 'edit', 'update', 'show');
    Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/update-status', [CommentController::class, 'updateStatus'])->name('comments.update-status');

    Route::resource('contacts', ContactRequestController::class)->only(['index', 'show', 'destroy']);

    Route::resource('menus', MenuController::class);
    Route::resource('menu-items', MenuItemController::class);

    // Setting
    Route::resource('settings', SettingController::class)->only([
        'index',
        'edit',
        'update'
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

// --- Route động cho Post và Category (đặt ở cuối cùng) ---
Route::get('{path}', [App\Http\Controllers\Frontend\DynamicRouteController::class, 'handle'])
    ->where('path', '.*') // Cho phép `path` chứa dấu gạch chéo `/`
    ->name('dynamic.resolve');