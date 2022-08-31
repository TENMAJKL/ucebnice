<?php

use Lemon\Contracts\Http\Session;

it('wont let unauthorized user')
    ->request('/')
    ->assertLocation('login')
;

it('will let authorised user', function() {
    $session = mock(Session::class)->expect(
        has: fn($key) => $key === 'email'
    );
    $this->application->add(get_class($session), $session);
    $this->application->alias(Session::class, get_class($session));

    $this->request('/')
         ->assertOk()
    ;
});

