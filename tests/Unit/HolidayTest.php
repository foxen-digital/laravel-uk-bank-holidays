<?php

namespace Foxen\BankHolidays\Tests\Unit;

use Carbon\Carbon;
use Foxen\BankHolidays\Data\Holiday;

it('can be created with all properties', function () {
    $holiday = new Holiday(
        title: "New Year's Day",
        date: '2026-01-01',
        notes: '',
        bunting: true,
        territory: 'england-and-wales'
    );

    expect($holiday->title)->toBe("New Year's Day")
        ->and($holiday->date)->toBe('2026-01-01')
        ->and($holiday->notes)->toBe('')
        ->and($holiday->bunting)->toBeTrue()
        ->and($holiday->territory)->toBe('england-and-wales');
});

it('can convert date to carbon instance', function () {
    $holiday = new Holiday(
        title: "New Year's Day",
        date: '2026-01-01',
        notes: '',
        bunting: true,
        territory: 'england-and-wales'
    );

    $carbon = $holiday->toCarbon();

    expect($carbon)->toBeInstanceOf(Carbon::class)
        ->and($carbon->format('Y-m-d'))->toBe('2026-01-01');
});

it('can be converted to array', function () {
    $holiday = new Holiday(
        title: "New Year's Day",
        date: '2026-01-01',
        notes: 'Some notes',
        bunting: true,
        territory: 'england-and-wales'
    );

    $array = $holiday->toArray();

    expect($array)->toBe([
        'title' => "New Year's Day",
        'date' => '2026-01-01',
        'notes' => 'Some notes',
        'bunting' => true,
        'territory' => 'england-and-wales',
    ]);
});
