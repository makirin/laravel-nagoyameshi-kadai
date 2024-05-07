<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Subscribed;
use App\Http\Middleware\NotSubscribed;
use App\Http\Controllers;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
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
    Route::get('restaurants/index', [App\Http\Controllers\RestaurantController::class, 'index'])->middleware(['auth', 'verified'])->name('restaurants.index');
    Route::get('restaurants/show={restaurant}', [App\Http\Controllers\RestaurantController::class, 'show'])->middleware(['auth', 'verified'])->name('restaurants.show');
    Route::get('subscription/create', [SubscriptionController::class, 'create'])->middleware(['auth', 'verified', 'not.subscribed'])->name('subscription.create');
    Route::post('subscription', [SubscriptionController::class, 'store'])->middleware(['auth', 'verified', 'not.subscribed'])->name('subscription.store');
    Route::get('subscription/edit', [SubscriptionController::class, 'edit'])->middleware(['auth', 'verified', 'subscribed'])->name('subscription.edit');
    Route::patch('subscription', [SubscriptionController::class, 'update'])->middleware(['auth', 'verified', 'subscribed'])->name('subscription.update');
    Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->middleware(['auth', 'verified', 'subscribed'])->name('subscription.cancel');
    Route::delete('subscription', [SubscriptionController::class, 'destroy'])->middleware(['auth', 'verified', 'subscribed'])->name('subscription.destroy');
    Route::resource('restaurants.reviews', ReviewController::class)->only(['index'])->middleware(['auth', 'verified']);
    Route::resource('restaurants.reviews', ReviewController::class)->except(['index','show'])->middleware(['auth', 'verified', 'subscribed']);
    Route::get('reservations', [ReservationController::class, 'index'])->middleware(['auth', 'verified', 'subscribed'])->name('reservations.index');
    Route::get('reservations/{restaurant}/reservations/create', [ReservationController::class, 'create'])->middleware(['auth', 'verified', 'subscribed'])->name('restaurants.reservations.create');
    Route::post('reservations/{restaurant}/reservations', [ReservationController::class, 'store'])->middleware(['auth', 'verified', 'subscribed'])->name('restaurants.reservations.store');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->middleware(['auth', 'verified', 'subscribed'])->name('reservations.destroy');
});


