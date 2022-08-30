<?php

namespace App\Controllers\Auth;

use Lemon\Database\Database;
use Lemon\Http\Request;
use Lemon\Http\Session;
use Lemon\Http\Response;
use Lemon\Templating\Template;

class Login
{
    public function post(Request $request, Database $database, Session $session): Response
    {
        $validation = $request->validate([
            'email' => 'email|max:255',
            'password' => 'max:255',
        ]);

        if (!$validation) {
            return template('login', error: 'Špatná data.');
        }

        $result = $database->query('SELECT * FROM users WHERE email=:email', email:$request->email)
                           ->fetchAll()
                        ;

        if (!$result) {
            return template('login', error: 'Tento uživatel neexistuje.');
        }

        $password = $request[0]['password'];

        if (!password_verify($request->password, $password)) {
            return template('login', error: 'Špatné heslo');
        }

        $session->set('email', $request->email);

        return redirect('/');
    }

    public function get(): Template
    {
        return template('login');
    }
}
