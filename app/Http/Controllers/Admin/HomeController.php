<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        $total_users = User::get()->count();
        $total_premium_users = DB::table('subscriptions')->select('stripe_status')->get()->count();
        $total_free_users = $total_users - $total_premium_users;
        $total_restaurants = Restaurant::get()->count();
        $total_reservations = Reservation::get()->count();
        $sales_for_this_month = $total_premium_users * 300;
        return view('admin.home', compact('total_users', 'total_premium_users', 'total_free_users', 'total_restaurants', 'total_reservations', 'sales_for_this_month'));
    }
}
