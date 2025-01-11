<?php

declare(strict_types=1);

namespace Elegantly\Referrer\Tests;

use Elegantly\Referrer\CaptureReferrerMiddleware;
use Elegantly\Referrer\Facades\Referrer;
use Elegantly\Referrer\ReferrerServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['router']->get('/', fn () => 'ok')->middleware(CaptureReferrerMiddleware::class);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Elegantly\\Referrer\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ReferrerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-referrer_table.php.stub';
        $migration->up();
        */
    }
}
