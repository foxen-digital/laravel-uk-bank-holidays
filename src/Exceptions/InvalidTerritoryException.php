<?php

namespace Foxen\BankHolidays\Exceptions;

use InvalidArgumentException;

class InvalidTerritoryException extends InvalidArgumentException
{
    public static function make(string $territory): self
    {
        return new self(
            "Invalid territory [{$territory}]. Valid territories: england-and-wales, scotland, northern-ireland"
        );
    }
}
