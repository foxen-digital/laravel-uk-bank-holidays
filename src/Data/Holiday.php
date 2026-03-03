<?php

namespace Foxen\BankHolidays\Data;

use Carbon\Carbon;

readonly class Holiday
{
    public function __construct(
        public string $title,
        public string $date,
        public string $notes,
        public bool $bunting,
        public string $territory,
    ) {}

    public function toCarbon(): Carbon
    {
        return Carbon::parse($this->date);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'date' => $this->date,
            'notes' => $this->notes,
            'bunting' => $this->bunting,
            'territory' => $this->territory,
        ];
    }
}
