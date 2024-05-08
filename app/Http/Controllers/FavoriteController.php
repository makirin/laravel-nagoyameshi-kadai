<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function index(){
        $favorite_restaurants = Auth::user()->favorite_restaurants()->orderBy('created_at', 'desc')->paginate(15);
        
        return view('favorites.index', compact('favorite_restaurants')); 
    }

    public function store(Restaurant $restaurant, $id) {
        $user = Auth::user();
        Restaurant::find($id)->users()->attach($user->id);
        return back()->with('flash_message', 'お気に入りに登録しました。');
    }

    public function destroy(Restaurant $restaurant, $id){
        $user = Auth::user();
        Restaurant::find($id)->users()->detach($user->id);
        return back()->with('flash_message', 'お気に入りを解除しました。');
    }
}
