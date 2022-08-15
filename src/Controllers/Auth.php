<?php

namespace App\Controllers;

use DateInterval;
use DateTime;
use Lemon\Database\Database;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Http\Session;
use Lemon\Kernel\Application;
use Lemon\Support\Types\Str;
use Lemon\Templating\Template;

class Auth
{
    public function login(Request $request, Database $database, Session $session): Response
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

    public function register(Request $request, Database $database, Session $session, Application $app): Template
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

        echo $token;

        mail(
            $request->email,
            'Ověření',
            (string) template('mail.verify', token: $token, url: $_SERVER['HTTP_HOST']),
            "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: silder.hologram@gmail.com"
        );

        return template('register', email: true, years: $years);
    }

    public function verify($token, Session $session, Database $database): Response
    {
        if (!$session->has('verification_token') || $token !== $session->get('verification_token')) {
            return redirect('register');
        }

        $result = $database->query('SELECT created_at FROM users WHERE email=:email',
            email: $session->get('email')
        )->fetchAll();

        $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $result[0]['created_at']);

        $session->remove('verification_token');

        if ($created_at->add(new DateInterval('P15M'))->getTimestamp() < time()) {
            $session->remove('email');
            return redirect('register');
        }

        return redirect('/');
    }
}
