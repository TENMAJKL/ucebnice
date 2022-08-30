<?php

include __DIR__.'/vendor/autoload.php';

use App\Middlewares\Auth;
use Lemon\Http\Middlewares\Cors;
use Lemon\Kernel\Application;
use Lemon\Lemon\Squeezer\Squeezer;
use Lemon\Protection\Middlwares\Csrf;

$application = new Application(__DIR__);

// --- Loading default Lemon services ---
$application->loadServices();

// --- Loading Zests for services ---
$application->loadZests();

// --- Loading Error/Exception handlers ---
$application->loadHandler();

$application->get('config')->load();

/** @var \Lemon\Routing\Router $router */
$router = $application->get('routing');

$router->file('routes.web')
       ->middleware(Csrf::class)
;

$router->file('routes.api')
       ->middleware(Cors::class)
       ->middleware([Auth::class, 'onlyAuthenticated'])
       ->prefix('api')
;

/** @var \Lemon\Validation\Validator $validation*/
$validation = $application->get('validation');

$validation->rules()->rule('mail', function(string $target) {
    return str_ends_with($target, '@'.env('EMAIL'));
});

Squeezer::init($application);

return $application;
