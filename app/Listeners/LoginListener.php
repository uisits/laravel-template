<?php

namespace App\Listeners;

use App\Models\User;

class LoginListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(\Illuminate\Auth\Events\Login $event): void
    {
        $user = $event->user;

        // Assign roles
    }

}