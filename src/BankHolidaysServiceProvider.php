<?php

namespace Foxen\BankHolidays;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Foxen\BankHolidays\Commands\BankHolidaysCommand;

class BankHolidaysServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-uk-bank-holidays')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_uk_bank_holidays_table')
            ->hasCommand(BankHolidaysCommand::class);
    }
}
