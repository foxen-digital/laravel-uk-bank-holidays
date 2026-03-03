<?php

namespace Foxen\BankHolidays;

use Carbon\Carbon;
use Foxen\BankHolidays\Data\Holiday;
use Foxen\BankHolidays\Enums\Territory;
use Foxen\BankHolidays\Exceptions\ApiConnectionException;
use Foxen\BankHolidays\Exceptions\InvalidConfigException;
use Foxen\BankHolidays\Exceptions\InvalidDateException;
use Foxen\BankHolidays\Exceptions\InvalidTerritoryException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BankHolidays
{
    protected string $apiUrl;

    protected string $defaultTerritory;

    protected int $cacheDuration;

    protected string $cacheKeyPrefix;

    public function __construct()
    {
        $this->validateConfig();

        $this->apiUrl = config('uk-bank-holidays.api_url');
        $this->defaultTerritory = config('uk-bank-holidays.default_territory');
        $this->cacheDuration = (int) config('uk-bank-holidays.cache_duration');
        $this->cacheKeyPrefix = config('uk-bank-holidays.cache_key_prefix');
    }

    public function isHoliday(
        string|Carbon $date,
        ?string $territory = null,
    ): bool {
        $dateString = $this->parseDate($date);
        $territory = $this->validateTerritory(
            $territory ?? $this->defaultTerritory,
        );

        $holidays = $this->getHolidaysForTerritory($territory);

        return $holidays->contains('date', $dateString);
    }

    public function forYear(int $year, ?string $territory = null): Collection
    {
        $territory = $this->validateTerritory(
            $territory ?? $this->defaultTerritory,
        );

        $holidays = $this->getHolidaysForTerritory($territory);

        return $holidays
            ->filter(function (Holiday $holiday) use ($year) {
                return Carbon::parse($holiday->date)->year === $year;
            })
            ->sortBy('date')
            ->values();
    }

    public function next(
        ?string $fromDate = null,
        ?string $territory = null,
    ): ?Holiday {
        $fromDate = $fromDate
            ? $this->parseDate($fromDate)
            : now()->format('Y-m-d');
        $territory = $this->validateTerritory(
            $territory ?? $this->defaultTerritory,
        );

        $holidays = $this->getHolidaysForTerritory($territory);

        return $holidays->first(function (Holiday $holiday) use ($fromDate) {
            return $holiday->date > $fromDate;
        });
    }

    public function between(
        string $startDate,
        string $endDate,
        ?string $territory = null,
    ): Collection {
        $startDate = $this->parseDate($startDate);
        $endDate = $this->parseDate($endDate);
        $territory = $this->validateTerritory(
            $territory ?? $this->defaultTerritory,
        );

        if ($startDate > $endDate) {
            throw new InvalidDateException(
                'Start date cannot be after end date',
            );
        }

        $holidays = $this->getHolidaysForTerritory($territory);

        return $holidays
            ->filter(function (Holiday $holiday) use ($startDate, $endDate) {
                return $holiday->date >= $startDate &&
                    $holiday->date <= $endDate;
            })
            ->sortBy('date')
            ->values();
    }

    public function get(
        string|Carbon $date,
        ?string $territory = null,
    ): ?Holiday {
        $dateString = $this->parseDate($date);
        $territory = $this->validateTerritory(
            $territory ?? $this->defaultTerritory,
        );

        $holidays = $this->getHolidaysForTerritory($territory);

        return $holidays->firstWhere('date', $dateString);
    }

    protected function getHolidaysForTerritory(string $territory): Collection
    {
        $data = $this->getApiData();

        return $this->parseHolidays(
            $data[$territory]['events'] ?? [],
            $territory,
        );
    }

    protected function getApiData(): array
    {
        $cacheKey = "{$this->cacheKeyPrefix}:all";

        return Cache::remember(
            $cacheKey,
            $this->cacheDuration,
            fn () => $this->fetchFromApi(),
        );
    }

    protected function fetchFromApi(): array
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl);

            if (! $response->successful()) {
                throw ApiConnectionException::make($response->status());
            }

            return $response->json();
        } catch (ConnectionException $e) {
            // Try to return stale cache if available
            throw ApiConnectionException::make(0);
        }
    }

    protected function parseHolidays(
        array $events,
        string $territory,
    ): Collection {
        return collect($events)->map(function (array $event) use ($territory) {
            return new Holiday(
                title: $event['title'],
                date: $event['date'],
                notes: $event['notes'] ?? '',
                bunting: $event['bunting'] ?? false,
                territory: $territory,
            );
        });
    }

    protected function parseDate(string|Carbon $date): string
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }

        // Try to parse as Y-m-d with strict validation
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            try {
                $carbon = Carbon::createFromFormat('Y-m-d', $date);
                if ($carbon && $carbon->format('Y-m-d') === $date) {
                    return $date;
                }
            } catch (\Exception $e) {
                // Fall through to throw exception
            }
        }

        // Try to parse as any valid date format
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            throw InvalidDateException::make($date);
        }
    }

    protected function validateTerritory(string $territory): string
    {
        $validTerritories = Territory::values();

        if (! in_array($territory, $validTerritories, true)) {
            throw InvalidTerritoryException::make($territory);
        }

        return $territory;
    }

    public function clearCache(?string $territory = null): void
    {
        // We cache the full API response under one key, so territory param is ignored
        Cache::forget("{$this->cacheKeyPrefix}:all");
    }

    private function validateConfig(): void
    {
        $config = config('uk-bank-holidays');

        if (! is_array($config)) {
            throw InvalidConfigException::make('uk-bank-holidays');
        }

        foreach ($config as $key => $value) {
            if (! isset($value) || (is_string($value) && trim($value) === '')) {
                throw InvalidConfigException::make($key);
            }
        }
    }
}
