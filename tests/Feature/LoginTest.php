<?php

it('logins user', function() {
    env('EMAIL', 'bar.baz');
    $this->request('/login', 'GET', body: 'email=foo@bar.baz&password=foo')
         ->assertOk()
    ;
    expect($this->application->get('session')->has('email'))
        ->toBe(false)
    ;
         
});

