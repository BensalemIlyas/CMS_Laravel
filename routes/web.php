<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'accueil'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/articles', [DashboardController::class, 'site'])->middleware(['auth', 'verified']);


Route::get('/posts', [PostsController::class, 'post'])->name('posts');
Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}', [PostsController::class, 'show'])->name('post.show');
Route::post("/posts/supprimer",[PostsController::class,"deletePost"])->name("post.delete");
Route::post('/comment', [PostsController::class, 'storeComment'])->name('comment.store');

Route::get('/menu', [MenuController::class, 'menu'])->name('menu');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/save-menu', [MenuController::class, 'save'])->name('save.menu');
Route::post('/menu', [MenuController::class, 'sauvegarder']);
Route::post('/menu', [MenuController::class, 'saveImage'])->name('save.site');



Route::get('/theme', [ThemeController::class, 'theme'])->name('theme');
Route::get('/theme/{id}', [ThemeController::class, 'show'])->name('theme.show');
Route::post('/save-theme', [ThemeController::class, 'save'])->name('save.theme');

Route::post('/comments/{id}', [PostsController::class, 'toggleStatus'])->name('comments.toggle-status');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
