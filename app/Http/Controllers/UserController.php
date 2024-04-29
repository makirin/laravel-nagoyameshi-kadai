<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(User $user){
        $user = Auth::user();

        return view('user.index', compact('user'));
    }

    public function edit(User $user){
        $user = Auth::user();
        if ($user->id !== Auth::id()) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'regex:/\A[ァ-ヴー\s]+\z/u', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'postal_code' => ['required', 'digits:7'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits_between:10, 11'],
            'birthday' => ['nullable', 'digits:8'],
            'occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        if ($user->id !== Auth::id()) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        } else {
            $user->update(['name' => $request->input('name')]);
            $user->update(['kana' => $request->input('kana')]);
            $user->update(['email' => $request->input('email')]);
            $user->update(['postal_code' => $request->input('postal_code')]);
            $user->update(['address' => $request->input('address')]);
            $user->update(['phone_number' => $request->input('phone_number')]);
            $user->update(['birthday' => $request->input('birthday')]);
            $user->update(['occupation' => $request->input('occupation')]);
            return redirect()->route('user.index', compact('user'))->with('flash_message', '会員情報を編集しました。');
        } 

    }
}
