<?php

namespace Elegantly\Referer\Tests;

use Elegantly\Referer\CaptureRefererMiddleware;
use Elegantly\Referer\Facades\Referer;
use Elegantly\Referer\RefererServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['router']->get('/', fn () => Referer::getSources())->middleware(CaptureRefererMiddleware::class);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Elegantly\\Referer\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            RefererServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-referer_table.php.stub';
        $migration->up();
        */
    }
}
