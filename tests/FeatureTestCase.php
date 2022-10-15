<?php

namespace GPapakitsos\LaravelTraits\Tests;

use GPapakitsos\LaravelTraits\Tests\Models\Country;
use GPapakitsos\LaravelTraits\Tests\Models\User;
use GPapakitsos\LaravelTraits\TraitsServiceProvider;
use Orchestra\Testbench\TestCase;

class FeatureTestCase extends TestCase
{
    public $route_prefix;
    public $country;
    public $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        User::factory()->count(49)->create();
        $this->country = Country::factory()->create();
        $this->user = User::factory()->create([
            'name' => 'George Papakitsos',
            'email' => 'papakitsos_george@yahoo.gr',
            'active' => true,
            'country_id' => $this->country->id,
            'created_at' => '1981-04-23 10:00:00',
            'updated_at' => null,
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            TraitsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app->config->set('app.locale', 'el');

        $app->config->set('database.default', 'testbench');
        $app->config->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app->config->set('laraveltraits.TimestampsAccessor.format', 'd/m/Y H:i:s');
        $app->config->set('laraveltraits.ModelActive.field', 'active');
        $app->config->set('laraveltraits.ModelOrdering.field', 'ordering');
    }
}
