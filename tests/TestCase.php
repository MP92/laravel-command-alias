<?php
namespace Cmizzi\CommandAlias\Tests;

use Cmizzi\CommandAlias\CommandAliasServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra {
    /**
     * getPackageProviders
     *
     * @param  mixed $app
     * @return array
     */
    protected function getPackageProviders($app) {
        return [
            CommandAliasServiceProvider::class,
        ];
    }
}
