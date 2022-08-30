<?php

namespace App\Controllers\Auth;

use DateInterval;
use DateTime;
use Lemon\Database\Database;
use Lemon\Http\Session;
use Lemon\Http\Response;

class Verify
{
    public function get($token, Session $session, Database $database): Response
    {
        if (!$session->has('verification_token') || $token !== $session->get('verification_token')) {
            return redirect('register');
        }

        $result = $database->query('SELECT created_at FROM users WHERE email=:email',
            email: $session->get('email')
        )->fetchAll();

        $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $result[0]['created_at']);

        $session->remove('verification_token');

        if ($created_at->add(new DateInterval('P120S'))->getTimestamp() < time()) {
            $session->remove('email');
            return redirect('register');
        }

        return redirect('/');
    }

}
