<?php

namespace Tests;

use Lemon\Kernel\Application;
use Lemon\Testing\TestCase as BaseTestCase;
use Lemon\Support\Filesystem;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->application->get('config')
                          ->set('database.driver', 'sqlite')
                          ->set('database.file', __DIR__.'/database.db')
                      ;

        foreach (Filesystem::listDir(__DIR__.'/../database/migrations') as $file) {
            require $file;
        }
    }

    protected function tearDown(): void
    {
        unlink(__DIR__.'/database.db');
    }

    public function createApplication(): Application
    {
        return require __DIR__.'/../init.php';
    }
}
