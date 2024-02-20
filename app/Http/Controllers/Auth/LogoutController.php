<?php

namespace App\Http\Controllers\Auth;

use Filament\Http\Controllers\Auth\LogoutController as FilamentLogoutController;
use Illuminate\Http\Request;

class LogoutController extends FilamentLogoutController
{
    //https://laracasts.com/discuss/channels/laravel/laravel-filament-logout
    public function logout(Request $request)
    {
        return redirect('/logout');
    }
}

