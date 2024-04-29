<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


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

require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::get('users/index', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/show/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('restaurants/index', [Admin\RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('restaurants/show={restaurant}', [Admin\RestaurantController::class, 'show'])->name('restaurants.show');
    Route::get('restaurants/create', [Admin\RestaurantController::class, 'create'])->middleware(['auth', 'verified'])->name('restaurants.create');
    Route::post('restaurants/create', [Admin\RestaurantController::class, 'store'])->middleware(['auth', 'verified'])->name('restaurants.store');
    Route::get('restaurants/edit={restaurant}', [Admin\RestaurantController::class, 'edit'])->middleware(['auth', 'verified'])->name('restaurants.edit');
    Route::patch('restaurants/edit={restaurant}', [Admin\RestaurantController::class, 'update'])->middleware(['auth', 'verified'])->name('restaurants.update');
    Route::delete('restaurants/edit={restaurant}', [Admin\RestaurantController::class, 'destroy'])->middleware(['auth', 'verified'])->name('restaurants.destroy');
    Route::get('categories/index', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories/index', [Admin\CategoryController::class, 'store'])->middleware(['auth', 'verified'])->name('categories.store');
    Route::patch('categories/{id}', [Admin\CategoryController::class, 'update'])->middleware(['auth', 'verified'])->name('categories.update');
    Route::delete('categories/{id}', [Admin\CategoryController::class, 'destroy'])->middleware(['auth', 'verified'])->name('categories.destroy');
    Route::get('company/index', [Admin\CompanyController::class, 'index'])->name('company.index'); 
    Route::get('company/edit', [Admin\CompanyController::class, 'edit'])->middleware(['auth', 'verified'])->name('company.edit');
    Route::patch('company/index', [Admin\CompanyController::class, 'update'])->middleware(['auth', 'verified'])->name('company.update');
    Route::get('terms/index', [Admin\TermController::class, 'index'])->name('terms.index'); 
    Route::get('terms/edit', [Admin\TermController::class, 'edit'])->middleware(['auth', 'verified'])->name('terms.edit');
    Route::patch('terms/index', [Admin\TermController::class, 'update'])->middleware(['auth', 'verified'])->name('terms.update');
});

Route::group(['middleware' => 'guest:admin'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::resource('user', App\Http\Controllers\UserController::class)->middleware(['auth', 'verified']);
});
