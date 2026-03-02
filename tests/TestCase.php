<?php

namespace Foxen\BankHolidays\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Foxen\BankHolidays\BankHolidaysServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [BankHolidaysServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
}
