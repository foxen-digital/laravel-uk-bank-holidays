<?php

namespace Foxen\BankHolidays\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Foxen\BankHolidays\BankHolidays
 */
class BankHolidays extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Foxen\BankHolidays\BankHolidays::class;
    }
}
