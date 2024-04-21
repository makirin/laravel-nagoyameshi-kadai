<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
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
        // バリデーションを設定する
        $request->validate([
            'name' => 'required',
        ]);

        // フォームの入力内容をもとに、テーブルにデータを追加する
        $categories = new Category();
        $categories->name = $request->input('name');
        $categories->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを登録しました。');
    }    

    public function update(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);

        $categories->name = $request->input('name');
        $categories->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを編集しました。');
    }   
    
    public function destroy(Category $categories) {
        $categories->delete();
 
        return redirect()->route('admin.categories.index', compact('categories'))->with('flash_message', 'カテゴリを削除しました。');
    }
}
