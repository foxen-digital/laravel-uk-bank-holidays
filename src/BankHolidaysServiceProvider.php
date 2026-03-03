<?php

namespace Foxen\BankHolidays;

use Foxen\BankHolidays\Facades\BankHolidays as BankHolidaysFacade;
use Illuminate\Support\Facades\Blade;
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
        $package->name('laravel-uk-bank-holidays')->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BankHolidays::class, function () {
            return new BankHolidays();
        });
    }

    public function packageBooted(): void
    {
        Blade::if('bankholiday', function ($date = null, $territory = null) {
            return BankHolidaysFacade::isHoliday($date ?? now(), $territory);
        });
    }
}
