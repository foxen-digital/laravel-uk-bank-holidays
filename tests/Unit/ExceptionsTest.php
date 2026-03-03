<?php

namespace Foxen\BankHolidays\Tests\Unit;

use Foxen\BankHolidays\Exceptions\ApiConnectionException;
use Foxen\BankHolidays\Exceptions\InvalidDateException;
use Foxen\BankHolidays\Exceptions\InvalidTerritoryException;

it('can create InvalidTerritoryException', function () {
    $exception = InvalidTerritoryException::make('invalid-territory');

    expect($exception)->toBeInstanceOf(InvalidTerritoryException::class)
        ->and($exception->getMessage())->toBe(
            'Invalid territory [invalid-territory]. Valid territories: england-and-wales, scotland, northern-ireland'
        );
});

it('can create InvalidDateException', function () {
    $exception = InvalidDateException::make('not-a-date');

    expect($exception)->toBeInstanceOf(InvalidDateException::class)
        ->and($exception->getMessage())->toBe(
            'Invalid date format [not-a-date]. Expected Y-m-d format or Carbon instance.'
        );
});

it('can create ApiConnectionException', function () {
    $exception = ApiConnectionException::make(500);

    expect($exception)->toBeInstanceOf(ApiConnectionException::class)
        ->and($exception->getMessage())->toBe(
            'Failed to fetch bank holidays from GOV.UK API. Status code: 500'
        );
});
