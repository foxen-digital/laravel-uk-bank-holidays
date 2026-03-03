<?php

namespace Foxen\BankHolidays\Tests\Feature;

use Carbon\Carbon;
use Foxen\BankHolidays\Data\Holiday;
use Foxen\BankHolidays\Exceptions\InvalidDateException;
use Foxen\BankHolidays\Exceptions\InvalidTerritoryException;
use Foxen\BankHolidays\Facades\BankHolidays;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Set up fake API response
    Http::fake([
        'https://www.gov.uk/*' => Http::response(getBankHolidaysJson()),
    ]);
});

it('can check if a date is a bank holiday', function () {
    expect(BankHolidays::isHoliday('2026-01-01'))
        ->toBeTrue()
        ->and(BankHolidays::isHoliday('2026-01-02'))
        ->toBeFalse();
});

it('can check if a date is a bank holiday using carbon', function () {
    expect(BankHolidays::isHoliday(Carbon::parse('2026-01-01')))->toBeTrue();
});

it('respects territory when checking holidays', function () {
    // St Andrew's Day is Scotland only
    expect(BankHolidays::isHoliday('2026-11-30', 'scotland'))
        ->toBeTrue()
        ->and(BankHolidays::isHoliday('2026-11-30', 'england-and-wales'))
        ->toBeFalse();
});

it('throws exception for invalid date format', function () {
    BankHolidays::isHoliday('not-a-date');
})->throws(InvalidDateException::class);

it('throws exception for invalid calendar date', function () {
    BankHolidays::isHoliday('2026-99-99');
})->throws(InvalidDateException::class);

it('throws exception for invalid territory', function () {
    BankHolidays::forYear(2026, 'invalid-territory');
})->throws(InvalidTerritoryException::class);

it('can get all holidays for a year', function () {
    $holidays = BankHolidays::forYear(2026);

    expect($holidays)
        ->toBeInstanceOf(Collection::class)
        ->and($holidays)
        ->toHaveCount(8)
        ->and($holidays->first())
        ->toBeInstanceOf(Holiday::class);
});

it('returns holidays sorted by date', function () {
    $holidays = BankHolidays::forYear(2026);

    expect($holidays->first()->date)
        ->toBe('2026-01-01')
        ->and($holidays->last()->date)
        ->toBe('2026-12-28');
});

it('can get holidays for a specific territory', function () {
    $scottishHolidays = BankHolidays::forYear(2026, 'scotland');

    expect($scottishHolidays)->toHaveCount(9);
});

it('returns empty collection for years with no data', function () {
    $holidays = BankHolidays::forYear(2030);

    expect($holidays)
        ->toBeInstanceOf(Collection::class)
        ->and($holidays)
        ->toBeEmpty();
});

it('can get next holiday from today', function () {
    Carbon::setTestNow('2026-01-02');

    $next = BankHolidays::next();

    expect($next)
        ->toBeInstanceOf(Holiday::class)
        ->and($next->title)
        ->toBe('Good Friday');

    Carbon::setTestNow();
});

it('can get next holiday from specific date', function () {
    $next = BankHolidays::next('2026-12-20');

    expect($next)
        ->toBeInstanceOf(Holiday::class)
        ->and($next->title)
        ->toBe('Christmas Day');
});

it('returns null when no future holidays available', function () {
    $next = BankHolidays::next('2030-01-01');

    expect($next)->toBeNull();
});

it('excludes the provided date when finding next holiday', function () {
    // If we ask for next holiday from Jan 1st, we shouldn't get Jan 1st back
    $next = BankHolidays::next('2026-01-01');

    expect($next->date)->not->toBe('2026-01-01');
});

it('can get holidays between two dates', function () {
    $holidays = BankHolidays::between('2026-01-01', '2026-01-31');

    expect($holidays)
        ->toBeInstanceOf(Collection::class)
        ->and($holidays)
        ->toHaveCount(1)
        ->and($holidays->first()->title)
        ->toBe("New Year's Day");
});

it('returns empty collection when no holidays in range', function () {
    $holidays = BankHolidays::between('2026-02-01', '2026-02-14');

    expect($holidays)->toBeEmpty();
});

it('throws exception when start date is after end date', function () {
    BankHolidays::between('2026-12-31', '2026-01-01');
})->throws(InvalidDateException::class);

it('can get holiday details for a specific date', function () {
    $holiday = BankHolidays::get('2026-12-25');

    expect($holiday)
        ->toBeInstanceOf(Holiday::class)
        ->and($holiday->title)
        ->toBe('Christmas Day');
});

it('returns null when date is not a holiday', function () {
    $holiday = BankHolidays::get('2026-01-02');

    expect($holiday)->toBeNull();
});

it('can get holiday details using carbon', function () {
    $holiday = BankHolidays::get(Carbon::parse('2026-12-25'));

    expect($holiday)
        ->toBeInstanceOf(Holiday::class)
        ->and($holiday->title)
        ->toBe('Christmas Day');
});
