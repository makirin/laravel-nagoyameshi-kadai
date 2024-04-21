<?php

namespace App\Http\Controllers\admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if ($keyword !== null) {
            $users = User::where('name', 'like', "%{$keyword}%")
            ->orWhere('kana', 'like', "%{$keyword}%")
            ->paginate(15);
            $total = $users->count();
        } else {
            $users = User::paginate(15);
            $total = $total = $users->count();;
            $keyword = null;
        } 
        return view('admin.users.index', compact('users','total','keyword'));
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user','id'));
    }    
}
