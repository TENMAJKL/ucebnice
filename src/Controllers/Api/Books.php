<?php

namespace App\Controllers\Api;

use Lemon\Database\Database;
use Lemon\Http\Session;

class Books
{
    public function all(Database $database, Session $session)
    {
        $year = $database->query('SELECT year FROM users WHERE email=?', $session->get('email'));

        return $database->query('SELECT * FROM books WHERE year=?', $year)->fetchAll();
    }
}
