<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\RegularHoliday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(Request $request, Restaurant $restaurant)
    {
        $keyword = $request->keyword;
        $categories = Category::get();
        $category_id = $request->category_id;
        $price = $request->price;
        $sorts = [
            '掲載日が新しい順' => 'created_at desc',
            '価格が安い順' => 'lowest_price asc',
            '評価が高い順' => 'rating desc',
            '予約数が多い順' => 'reservation desc'

        ];
        $sort_query = [];
        $sorted = "created_at desc";

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        if ($keyword !== null) {
            $restaurants = Restaurant::where('name', 'like', "%{$keyword}%")
            ->orWhere('address', 'like', "%{$keyword}%")
            ->orwhereHas('categories', function($query) use ($keyword){
                $query->where('categories.name', 'like',"%{$keyword}%");
            })->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->count();
        } elseif ($category_id !== null) {
            $restaurants = Restaurant::whereHas('categories', function($query) use ($category_id){
                $query->where('categories.id', $category_id);
            })->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->count();
        } elseif ($price !== null) {
            $restaurants = Restaurant::where('lowest_price', '<=', $price)
            ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->total();
        } else {
            $restaurants = Restaurant::sortable ($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->count();
        } 
        return view('restaurants.index', compact('restaurants','total','keyword','categories','category_id','price','sorts','sorted'));
    }

    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', compact('restaurant'));
    }
} 
