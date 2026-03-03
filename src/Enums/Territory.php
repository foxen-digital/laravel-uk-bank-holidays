<?php

namespace Foxen\BankHolidays\Enums;

enum Territory: string
{
    case ENGLAND_WALES = 'england-and-wales';
    case SCOTLAND = 'scotland';
    case NORTHERN_IRELAND = 'northern-ireland';

    public function label(): string
    {
        return match ($this) {
            self::ENGLAND_WALES => 'England and Wales',
            self::SCOTLAND => 'Scotland',
            self::NORTHERN_IRELAND => 'Northern Ireland',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromString(string $value): self
    {
        return self::from($value);
    }
}
