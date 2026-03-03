<?php

namespace Foxen\BankHolidays\Tests\Feature;

use Foxen\BankHolidays\Exceptions\ApiConnectionException;
use Foxen\BankHolidays\Facades\BankHolidays;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        'gov.uk/*' => Http::response(\Foxen\BankHolidays\Tests\Feature\getBankHolidaysJson()),
    ]);
});

it('caches response for configured duration', function () {
    BankHolidays::forYear(2026);
    BankHolidays::forYear(2026);

    Http::assertSentCount(1); // Only one API call
});

it('uses cached data on subsequent requests', function () {
    // First call should hit the API
    $holidays1 = BankHolidays::forYear(2026);

    // Clear Http fake and set it to fail
    Http::fake([
        'gov.uk/*' => Http::response([], 500),
    ]);

    // Second call should use cache
    $holidays2 = BankHolidays::forYear(2026);

    expect($holidays1)->toHaveCount(8)
        ->and($holidays2)->toHaveCount(8);
});

it('can manually clear cache', function () {
    BankHolidays::forYear(2026);

    BankHolidays::clearCache();

    // After clearing, should make a new API call
    Http::fake([
        'gov.uk/*' => Http::response(\Foxen\BankHolidays\Tests\Feature\getBankHolidaysJson()),
    ]);

    BankHolidays::forYear(2026);

    Http::assertSentCount(1);
});

it('can clear cache for specific territory', function () {
    BankHolidays::forYear(2026, 'england-and-wales');
    BankHolidays::forYear(2026, 'scotland');

    BankHolidays::clearCache('england-and-wales');

    // After clearing one territory, other should still be cached
    Http::fake([
        'gov.uk/*' => Http::response(\Foxen\BankHolidays\Tests\Feature\getBankHolidaysJson()),
    ]);

    BankHolidays::forYear(2026, 'scotland');

    Http::assertSentCount(0); // No API call because Scotland is still cached
});

it('throws exception when api fails with no cache', function () {
    Cache::flush();

    Http::fake(function ($request) {
        throw new ConnectionException('Connection failed');
    });

    BankHolidays::forYear(2026);
})->throws(ApiConnectionException::class);
