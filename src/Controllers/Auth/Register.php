<?php

namespace App\Controllers\Auth;

use Lemon\Support\Types\Str;
use Lemon\Templating\Template;
use Lemon\Kernel\Application;
use Lemon\Database\Database;
use Lemon\Http\Request;
use Lemon\Http\Session;

class Register
{
    public function post(Request $request, Database $database, Session $session, Application $app): Template
    {
        $validation = $request->validate([
            'email' => 'mail|max:255',
            'password' => 'max:255',
            'year' => 'max:16',
        ]);

        $years = explode("\n", file_get_contents($app->file('years', 'txt')));

        if (!$validation) {
            return template('register', error: 'Špatná data.', years: $years);
        }

        $user = $database->query('SELECT * FROM users WHERE email=:email',
            email: $request->email
        )->fetchAll();

        if (count($user) > 0) {
            return template('register', error: 'Uživatel s tímto emailem již existuje.', years: $years);
        }

        $year = array_key_first(array_filter($years, fn($item) => $item === $request->year - 1)) + 1;
        if (is_null($year)) {
            return template('register', error: 'Špatná data.', years: $years);
        }

        $database->query('INSERT INTO users (email, password, year, created_at) VALUES (:email, :password, :year, datetime("now"))', 
            email: $request->email,
            password: password_hash($request->password, PASSWORD_ARGON2ID),
            year: $year
        );

        $token = sha1(str_shuffle(Str::random(32).time()));

        $session->set('verification_token', $token);
        $session->set('email', $request->email);

        mail(
            $request->email,
            'Ověření',
            (string) template('mail.verify', token: $token, url: $_SERVER['HTTP_HOST']),
            "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: silder.hologram@gmail.com"
        );

        return template('register', email: true, years: $years);
    }

    public function get(Application $app): Template
    {
        $years = explode("\n", file_get_contents($app->file('years', 'txt')));
        return template('register', years: $years);
    }
}
