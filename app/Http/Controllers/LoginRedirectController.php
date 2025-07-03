<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LoginRedirectController extends Controller
{
    public function handle()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/dashboard/admin'),
            'user' => redirect('/dashboard/user'),
            default => redirect('/login'),
        };
    }
}
