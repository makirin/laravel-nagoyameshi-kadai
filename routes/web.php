<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RestaurantController;

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

require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::get('users/index', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/show/{id}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::get('restaurants/index', [Admin\RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('restaurants/show={restaurant}', [Admin\RestaurantController::class, 'show'])->name('restaurants.show');
    Route::get('restaurants/create', [RestaurantController::class, 'create'])->middleware(['auth', 'verified'])->name('restaurants.create');
    Route::post('restaurants/create', [RestaurantController::class, 'store'])->middleware(['auth', 'verified'])->name('restaurants.store');
    Route::get('restaurants/edit={restaurant}', [restaurantController::class, 'edit'])->middleware(['auth', 'verified'])->name('restaurants.edit');
    Route::patch('restaurants/edit={restaurant}', [restaurantController::class, 'update'])->middleware(['auth', 'verified'])->name('restaurants.update');
    Route::delete('restaurants/edit={restaurant}', [restaurantController::class, 'destroy'])->middleware(['auth', 'verified'])->name('restaurants.destroy');
});