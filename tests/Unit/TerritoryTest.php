<?php

namespace Foxen\BankHolidays\Tests\Unit;

use Foxen\BankHolidays\Enums\Territory;

it('has correct territory cases', function () {
    expect(Territory::cases())->toHaveCount(3)
        ->and(Territory::ENGLAND_WALES->value)->toBe('england-and-wales')
        ->and(Territory::SCOTLAND->value)->toBe('scotland')
        ->and(Territory::NORTHERN_IRELAND->value)->toBe('northern-ireland');
});

it('returns correct labels', function () {
    expect(Territory::ENGLAND_WALES->label())->toBe('England and Wales')
        ->and(Territory::SCOTLAND->label())->toBe('Scotland')
        ->and(Territory::NORTHERN_IRELAND->label())->toBe('Northern Ireland');
});

it('returns all values', function () {
    $values = Territory::values();

    expect($values)->toBe([
        'england-and-wales',
        'scotland',
        'northern-ireland',
    ]);
});

it('can be created from string', function () {
    $territory = Territory::fromString('scotland');

    expect($territory)->toBe(Territory::SCOTLAND);
});

it('throws exception for invalid territory string', function () {
    Territory::fromString('invalid');
})->throws(\ValueError::class);
