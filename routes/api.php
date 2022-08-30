<?php

use App\Controllers\Api\Books;
use Lemon\Route;

Route::get('/books', [Books::class, 'all']);
