# Changelog

All notable changes to `laravel-uk-bank-holidays` will be documented in this file.

## v1.0.0 - Initial Release - 2026-03-03

### Laravel UK Bank Holidays - Initial Release

A Laravel package providing easy access to official UK bank holiday data from GOV.UK with intelligent caching and territorial support.

#### Features

- ✅ Fetches data directly from the official GOV.UK bank holidays API
- ✅ Intelligent caching with configurable duration (single cache key for all territories)
- ✅ Support for all UK territories: England & Wales, Scotland, and Northern Ireland
- ✅ Clean, Laravel-native API with Facade support
- ✅ Blade directive for conditional rendering (`@bankholiday`)
- ✅ Carbon integration for flexible date handling
- ✅ Comprehensive error handling with specific exceptions

#### Available Methods

- `isHoliday($date, $territory)` - Check if a date is a bank holiday
- `forYear($year, $territory)` - Get all holidays for a year
- `next($fromDate, $territory)` - Get the next bank holiday
- `between($startDate, $endDate, $territory)` - Get holidays within a date range
- `get($date, $territory)` - Get holiday details for a specific date
- `clearCache()` - Clear cached API data

#### Installation

```bash
composer require foxen/laravel-uk-bank-holidays

```
#### Requirements

- PHP ^8.4
- Laravel ^11.0 or ^12.0
