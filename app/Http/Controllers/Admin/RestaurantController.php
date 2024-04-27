<?php

namespace App\Http\Controllers\Admin;
use App\Models\Restaurants;
use App\Models\Category;
use App\Models\RegularHoliday;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $restaurants = Restaurants::where('name', 'like', "%{$keyword}%")->paginate(15);
            $total = $restaurants->count();
        } else {
            $restaurants = Restaurants::paginate(15);
            $total = $restaurants->count();
            $keyword = null;
        } 
        return view('admin.restaurants.index', compact('restaurants','total','keyword'));
    }

    public function show(Restaurants $restaurant)
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }
    
    public function create()
     {
        $categories = Category::get();
        $restaurant = Restaurants::get();
        $regular_holidays = RegularHoliday::get();
        return view('admin.restaurants.create',compact('restaurant','categories','regular_holidays'));
     }

     public function store(Request $request) {
        // バリデーションを設定する
        $request->validate([
            'name' => 'required',
            'img' => 'file|mimes:jpg、jpeg、png、bmp、gif、svg、webp|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|integer|min:0|lte:highest_price',
            'highest_price' => 'required|integer|min:0|gte:lowest_price',
            'postal_code'=> 'required|integer|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|integer|min:0',
        ]);

        // フォームの入力内容をもとに、テーブルにデータを追加する
        $restaurant = new Restaurants();
        $restaurant->name = $request->input('name');
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('storage/app/public/restaurants');
            $restaurant->img = $image;
        } else {
            $restaurant->img ='' ;
        }
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        $restaurant->save();

        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        $regular_holiday_ids = array_filter($request->input('regular_holiday_ids'));
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
    }    
    
    public function edit(Restaurants $restaurant){
        $categories = Category::get();
        $regular_holidays = RegularHoliday::get();
        $category_ids = $restaurant->categories->pluck('id')->toArray();
        return view('admin.restaurants.edit', compact('restaurant','categories','category_ids','regular_holidays'));
    }
    
     public function update(Request $request, Restaurants $restaurant){
        $request->validate([
            'name' => 'required',
            'img' => 'file|mimes:jpg、jpeg、png、bmp、gif、svg、webp|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|integer|min:0|lte:highest_price',
            'highest_price' => 'required|integer|min:0|gte:lowest_price',
            'postal_code'=> 'required|integer|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|integer|min:0',
        ]);

        $restaurant->name = $request->input('name');
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('storage/app/public/restaurants');
            $restaurant->img = $image;
        } else {
            $restaurant->img ='' ;
        }
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        $restaurant->save();

        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        $regular_holiday_ids = array_filter($request->input('regular_holiday_ids'));
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        return redirect()->route('admin.restaurants.show', compact('restaurant'))->with('flash_message', '店舗を編集しました。');

    }    
    public function destroy(Restaurants $restaurant) {
        $restaurant->delete();
 
        return redirect()->route('admin.restaurants.index', compact('restaurant'))->with('flash_message', '店舗を削除しました。');
    }
}
