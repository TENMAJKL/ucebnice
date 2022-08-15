<?php

use Lemon\Support\Filesystem;
use Lemon\Terminal;

Terminal::command('migrate', function() {
    Terminal::out('<div class="text-green">Migrating...</div>');
    foreach (Filesystem::listDir(__DIR__.'/database/migrations') as $file) {
        require $file;
        Terminal::out('<div class="text-yellow">'.$file.' - done</div>');
    }
    Terminal::out('<div class="text-green">Done!</div>');
}, 'Migrates whole app');

Terminal::command('repl', function() {
    system('psysh init.php > `tty`');
}, 'Starts development repl');
