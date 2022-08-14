<?php

use Lemon\Http\Request;
use Lemon\Route;
use Lemon\Templating\Template;

it('has a homepage', function() {    
    /** @var \Lemon\Routing\Router */
    $router = Route::getAccessor();
    $response = $router->dispatch(new Request('/', '', 'get', [], '', []));
    expect($response->headers()['Location'])->toBe('login');

    $_SESSION['name'] = 'Majkel';
    $response = $router->dispatch(new Request('/', '', 'get', [], '', []));
    expect($response->body)->toBeInstanceOf(Template::class);
});
