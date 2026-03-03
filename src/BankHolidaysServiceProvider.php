<?php

namespace Foxen\BankHolidays;

use Foxen\BankHolidays\Commands\BankHolidaysCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BankHolidaysServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name("laravel-uk-bank-holidays")->hasConfigFile();
    }
}
