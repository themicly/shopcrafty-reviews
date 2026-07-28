<?php

namespace Themicly\Shopcrafty\Reviews\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Livewire\LivewireServiceProvider;
use Themicly\Shopcrafty\ShopcraftyServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [LivewireServiceProvider::class, ShopcraftyServiceProvider::class, \Themicly\Shopcrafty\Reviews\ReviewsServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']);
    }
}
