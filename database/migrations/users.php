<?php

use Lemon\DB;

DB::query('CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email varchar(255),
    password varchar(255),
    year int,
    created_at DATETIME 
)');
