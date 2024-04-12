<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
   /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
             'kana' => ['required', 'string', 'regex:/\A[ァ-ヴー\s]+\z/u', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
             'postal_code' => ['required', 'digits:7'],
             'address' => ['required', 'string', 'max:255'],
             'phone_number' => ['required', 'digits_between:10, 11'],
             'birthday' => ['nullable', 'digits:8'],
             'occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
             'kana' => $request->kana,
            'email' => $request->email,
            'password' => Hash::make($request->password),
             'postal_code' => $request->postal_code,
             'address' => $request->address,
             'phone_number' => $request->phone_number,
             'birthday' => $request->birthday,
             'occupation' => $request->occupation,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
