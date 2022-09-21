<?php

namespace App\Middlewares;

use Lemon\Contracts\Http\Session;

class Auth
{
    public function onlyAuthenticated(Session $session)
    {
        if (!$session->has('email') || $session->has('verification_token')) {
            return redirect('login');
        }
    }

    public function onlyGuest(Session $session)
    {
        if ($session->has('email') && !$session->has('verification_token')) {
            return redirect('/');
        }
    }
}
