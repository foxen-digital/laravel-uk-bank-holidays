<?php

namespace Foxen\BankHolidays\Facades;

use Foxen\BankHolidays\BankHolidays as BankHolidaysClass;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isHoliday(string|\Carbon\Carbon $date, ?string $territory = null)
 * @method static \Illuminate\Support\Collection forYear(int $year, ?string $territory = null)
 * @method static \Foxen\BankHolidays\Data\Holiday|null next(?string $fromDate = null, ?string $territory = null)
 * @method static \Illuminate\Support\Collection between(string $startDate, string $endDate, ?string $territory = null)
 * @method static \Foxen\BankHolidays\Data\Holiday|null get(string|\Carbon\Carbon $date, ?string $territory = null)
 * @method static void clearCache(?string $territory = null)
 *
 * @see \Foxen\BankHolidays\BankHolidays
 */
class BankHolidays extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BankHolidaysClass::class;
    }
}
