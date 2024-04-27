<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermController extends Controller
{
    public function index()
    {
        $id = 1;
        $term = Term::find($id);

        return view('admin.terms.index', compact('term'));
    }

    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term){
        $request->validate([
            'content' => 'required',
        ]);

        $trem->content = $request->input('content');
        $trem->save();

         return redirect()->route('admin.terms.update', compact('trem'))->with('flash_message', '利用規約を編集しました。');
    } 
}
