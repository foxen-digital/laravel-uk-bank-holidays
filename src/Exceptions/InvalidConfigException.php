<?php

namespace Foxen\BankHolidays\Exceptions;

use InvalidArgumentException;

class InvalidConfigException extends InvalidArgumentException
{
    public static function make(string $name): self
    {
        return new self(
            "Invalid config item [{$name}]. All config items must have a value",
        );
    }
}
