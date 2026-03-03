<?php

namespace Foxen\BankHolidays\Exceptions;

use InvalidArgumentException;

class InvalidDateException extends InvalidArgumentException
{
    public static function make(string $date): self
    {
        return new self(
            "Invalid date format [{$date}]. Expected Y-m-d format or Carbon instance."
        );
    }
}
