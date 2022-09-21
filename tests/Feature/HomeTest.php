<?php

it('wont let unauthorised user', function() {
    $this->session();

    $this->request('/')
         ->assertLocation('login')
    ;
});


it('will let authorised user', function() {
    
    $this->session(email: 'foo@bar.xyz');

    $this->request('/')
         ->assertOk()
    ;
});

