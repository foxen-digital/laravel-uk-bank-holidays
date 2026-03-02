<?php

namespace Foxen\BankHolidays\Commands;

use Illuminate\Console\Command;

class BankHolidaysCommand extends Command
{
    public $signature = 'laravel-uk-bank-holidays';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
