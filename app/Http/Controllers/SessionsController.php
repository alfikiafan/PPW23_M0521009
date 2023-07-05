<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class SessionsController extends Controller
{
    public function create(): View
    {
        return view('sessions.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (!auth()->attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Your email is wrong, please try again.',
                'password' => 'Your password is wrong, please try again.'
            ]);
        }


        session()->regenerate();
        return redirect('/')->with('success', 'You\'re logged in');
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Goodbye!');
    }
}
