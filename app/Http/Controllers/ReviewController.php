<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Review;
use App\Models\Review_restaurants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Restaurant $restaurant, Request $request)
    {
        $sorts = [
            '掲載日が新しい順' => 'created_at desc',
        ];
        $sort_query = [];
        $sorted = "created_at desc";

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        if  (! $request->user()?->subscribed('premium_plan')) { 
            $reviews = Review::whereHas('restaurant', function($query) use ($restaurant){
                $query->where('restaurant_id', $restaurant->id);
                })->sortable($sort_query)->orderBy('created_at', 'desc')->limit(3)->get();
            return view('reviews.index', compact('restaurant','reviews'));        
        } else {
            $reviews = Review::whereHas('restaurant', function($query) use ($restaurant){
                $query->where('restaurant_id', $restaurant->id);
                })->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(5);
            return view('reviews.index', compact('restaurant','reviews'));    
        } 
    }

    public function create(Restaurant $restaurant){
        return view('reviews.create', compact('restaurant'));
    }

    public function store(Request $request, Restaurant $restaurant, User $user){
        $request->validate([
            'score' => 'required|integer|in:1,2,3,4,5',
            'content' => 'required',
        ]);
        $user = Auth::User();
        $reviews = new Review();
        $reviews->score = $request->input('score');
        $reviews->content = $request->input('content');
        $reviews->restaurant_id = $restaurant->id;
        $reviews->user_id = $user->id;
        $reviews->save();
        Log::debug($reviews);
        return redirect()->route('restaurants.reviews.store', compact('restaurant'))->with('flash_message', 'レビューを投稿しました。');
    }

    public function edit(Restaurant $restaurant, Review $review, User $user){
        $user=Auth::user();

        if ($user->id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error_message', '不正なアクセスです。');
        } else {
            return view('reviews.edit', compact('restaurant', 'review'));
        }
    }
    
    public function update(Request $request, Restaurant $restaurant, Review $review){
        $request->validate([
            'score' => 'required|integer|in:1,2,3,4,5',
            'content' => 'required',
        ]);

        $user=Auth::user();

        if ($user->id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index')->with('error_message', '不正なアクセスです。');
        } else {
            Log::debug($review);
            $review->update(['score' => $request->input('score')]);
            $review->update(['content' => $request->input('content')]);
            $review->update(['restaurant_id' => $review->restaurant_id]);
            $review->update(['user_id' => $review->user_id]);
            Log::debug($review);
            // return redirect()->route('restaurants.reviews.update', compact('restaurant','review'))->with('flash_message', 'レビューを編集しました。');
        }
        return redirect()->route('restaurants.reviews.index', $restaurant->id)->with('flash_message', 'レビューを編集しました。');
    }

    public function destroy(Restaurant $restaurant, Review $review, User $user){
        $user=Auth::user();

        if ($user->id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error_message', '不正なアクセスです。');
        } else {
            Log::debug($review);
            $review->delete();
        }    
        return redirect()->route('restaurants.reviews.index', $restaurant->id)->with('flash_message', 'レビューを削除しました。');
    }
}
