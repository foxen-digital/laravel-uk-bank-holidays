<?php

namespace Foxen\BankHolidays\Exceptions;

use RuntimeException;

class ApiConnectionException extends RuntimeException
{
    public static function make(int $statusCode): self
    {
        return new self(
            "Failed to fetch bank holidays from GOV.UK API. Status code: {$statusCode}"
        );
    }
}
