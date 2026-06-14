<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('app.invalid_credentials'),
            ]);
        }

        // Block check
        if (Auth::user()->isBlocked()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('app.account_blocked_contact'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('recipes.index'))
            ->with('success', __('app.welcome_back', ['name' => Auth::user()->name]));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('recipes.index');
    }
}
