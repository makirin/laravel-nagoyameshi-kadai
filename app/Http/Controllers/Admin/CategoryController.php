<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        Log::debug("log1");
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $categories = Category::where('name', 'like', "%{$keyword}%")->paginate(15);
            $total = $categories->count();
        } else {
            $categories = Category::paginate(15);
            $total = $categories->count();
            $keyword = null;
        } 
        return view('admin.categories.index', compact('categories','total','keyword'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);

        $categories = new Category();
        $categories->name = $request->input('name');
        $categories->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを登録しました。');
    }    

    public function update(Request $request, Category $categories, $id) {
        Log::debug("log2");
        Log::debug($request);
        Log::debug($categories);
        $request->validate([
            'name' => 'required',
        ]);

        $categories = Category::find($id);
        Log::debug($categories);
        $categories->update(['name' => $request->input('name')]);
        Log::debug($categories);

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを編集しました。');
    }   
    
    public function destroy(Category $categories, $id) {
        $categories = Category::find($id);
        $categories->delete();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを削除しました。');
    }
}
