<?php

namespace Foxen\BankHolidays\Exceptions;

use Foxen\BankHolidays\Enums\Territory;
use InvalidArgumentException;

class InvalidTerritoryException extends InvalidArgumentException
{
    public static function make(string $territory): self
    {
        $territories = implode(', ', Territory::values());

        return new self(
            "Invalid territory [{$territory}]. Valid territories: {$territories}",
        );
    }
}
