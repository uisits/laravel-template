<?php

namespace App\Http\Controllers\Filament;

use Illuminate\Http\Request;

class LogoutController extends \Filament\Auth\Http\Controllers\LogoutController
{
    //https://laracasts.com/discuss/channels/laravel/laravel-filament-logout
    public function logout(Request $request)
    {
        return redirect('/logout');
    }
}
