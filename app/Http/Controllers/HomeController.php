<?php

namespace App\Http\Controllers;
use App\Models\Restaurants;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $highly_rated_restaurants = Restaurants::take(6)->get();
        Log::debug($highly_rated_restaurants);
        $categories = Category::get();
        Log::debug($categories);
        $new_restaurants = Restaurants::orderByDesc('created_at')->take(6)->get();
        Log::debug($new_restaurants);

        return view('home', compact('highly_rated_restaurants', 'categories', 'new_restaurants'));
    }

}
