<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);

        return view('users.index', compact('users'));
    }

    public function search()
    {
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $users = User::where('name', 'like', "%{$keyword}%")
            ->User::orWhere('kana', 'like', "%{$keyword}%")
            ->paginate(15);
            $total_count = $keyword->total();
        }    
        return view('users.index', compact('users','total_count'));
    }

    // showにする
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }    
}
