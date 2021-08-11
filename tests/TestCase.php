<?php

declare(strict_types=1);

namespace Actuallymab\LaravelComment\Tests;

use Actuallymab\LaravelComment\LaravelCommentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    private function setUpDatabase(): void
    {
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => __DIR__.'/migrations',
            '--realpath' => true,
        ]);

        require_once __DIR__.'/../src/create_comments_table.php.stub';

        (new \CreateCommentsTable)->up();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function getPackageProviders($app): array
    {
        return [
            LaravelCommentServiceProvider::class,
        ];
    }
}
