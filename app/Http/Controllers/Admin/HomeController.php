<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index() {
        $total_users = User::get()->count();
        Log::debug("----------------");
        Log::debug($total_users);
        $total_premium_users = DB::table('subscriptions')->select('stripe_status')->get()->count();
        Log::debug($total_premium_users);
        $total_free_users = $total_users - $total_premium_users;
        Log::debug($total_free_users);
        $total_restaurants = Restaurant::get()->count();
        Log::debug($total_restaurants);
        $total_reservations = Reservation::get()->count();
        Log::debug($total_reservations);
        $sales_for_this_month = $total_premium_users * 300;
        Log::debug($sales_for_this_month);
        return view('admin.home', compact('total_users', 'total_premium_users', 'total_free_users', 'total_restaurants', 'total_reservations', 'sales_for_this_month'));
    }
}
