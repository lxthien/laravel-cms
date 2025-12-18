<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SitemapController;
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
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RedirectController;
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


// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-posts.xml', [SitemapController::class, 'posts'])->name('sitemap.posts');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');


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
        ->name('categories.update-order')->middleware('permission:category-edit');
    // Update status (mới)
    Route::post('categories/{category}/update-status', [CategoryController::class, 'updateStatus'])
        ->name('categories.update-status')->middleware('permission:category-edit');

    // Posts
    Route::resource('posts', PostController::class);
    Route::get('/posts/category/{category}', [PostController::class, 'byCategory'])
        ->name('posts.by-category');

    // Pages Management
    Route::resource('pages', PageController::class);
    Route::patch('pages/{page}/update-order', [PageController::class, 'updateOrder'])
        ->name('pages.update-order')->middleware('permission:post-edit');
    Route::patch('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])
        ->name('pages.toggle-status')->middleware('permission:post-edit');

    Route::resource('comments', CommentController::class)->except('create', 'store', 'edit', 'update', 'show');
    Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/update-status', [CommentController::class, 'updateStatus'])->name('comments.update-status');

    Route::resource('contacts', ContactRequestController::class)->only(['index', 'show', 'destroy']);

    Route::resource('menus', MenuController::class);
    Route::post('menus/{menu}/update-structure', [MenuController::class, 'updateStructure'])->name('menus.update-structure');
    Route::resource('menu-items', MenuItemController::class);

    // Setting
    Route::resource('settings', SettingController::class)->only([
        'index',
        'edit',
        'update'
    ])->middleware('permission:settings-edit');

    // Tag Management
    Route::resource('tags', TagController::class);

    // Role Management
    Route::resource('roles', RoleController::class)->middleware('role:admin');

    // Redirect Manager
    Route::resource('redirects', RedirectController::class);
    Route::post('redirects/bulk-delete', [RedirectController::class, 'bulkDelete'])->name('redirects.bulk-delete');
    Route::post('redirects/{redirect}/toggle-status', [RedirectController::class, 'toggleStatus'])->name('redirects.toggle-status');
    Route::get('redirects-export', [RedirectController::class, 'exportCsv'])->name('redirects.export');
    Route::post('redirects-import', [RedirectController::class, 'importCsv'])->name('redirects.import');

    // Media Manager
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::get('/media/{id}', [MediaController::class, 'show'])->name('media.show');
    Route::put('/media/{id}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

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