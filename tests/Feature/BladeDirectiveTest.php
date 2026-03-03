<?php

namespace Foxen\BankHolidays\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        'https://www.gov.uk/*' => Http::response(getBankHolidaysJson()),
    ]);
});

it('renders content on bank holiday', function () {
    $view = Blade::render(
        '@bankholiday("2026-01-01") Holiday! @endbankholiday',
    );

    expect($view)->toContain('Holiday!');
});

it('hides content on non-bank holiday', function () {
    $view = Blade::render(
        '@bankholiday("2026-01-02") Holiday! @endbankholiday',
    );

    expect($view)->not->toContain('Holiday!');
});

it('supports else clause', function () {
    $view = Blade::render(
        '@bankholiday("2026-01-02") Holiday! @else Normal day @endbankholiday',
    );

    expect($view)
        ->not->toContain('Holiday!')
        ->and($view)
        ->toContain('Normal day');
});

it('checks today when no date provided', function () {
    \Carbon\Carbon::setTestNow('2026-01-01');

    $view = Blade::render('@bankholiday Holiday today! @endbankholiday');

    expect($view)->toContain('Holiday today!');

    \Carbon\Carbon::setTestNow();
});

it('respects territory parameter', function () {
    // St Andrew's Day is Scotland only
    $view = Blade::render(
        '@bankholiday("2026-11-30", "scotland") Scotland holiday! @endbankholiday',
    );

    expect($view)->toContain('Scotland holiday!');

    $view = Blade::render(
        '@bankholiday("2026-11-30", "england-and-wales") England holiday! @endbankholiday',
    );

    expect($view)->not->toContain('England holiday!');
});
