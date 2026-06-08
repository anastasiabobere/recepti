<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function profile(): View
    {
        $user    = Auth::user();
        $recipes = $user->recipes()->with('category')->latest()->paginate(9);
        return view('users.profile', compact('user', 'recipes'));
    }
}
