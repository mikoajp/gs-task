<?php

namespace App\ValueObjects;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use JsonSerializable;

readonly class ServiceDate implements JsonSerializable
{
    private function __construct(
        private ?Carbon $date
    ) {}

    public static function fromString(?string $dateString): self
    {
        if (empty($dateString)) {
            return new self(null);
        }

        try {
            return new self(Carbon::parse($dateString));
        } catch (\Exception $e) {
            Log::warning('Failed to parse date', [
                'date' => $dateString,
                'error' => $e->getMessage(),
            ]);

            return new self(null);
        }
    }

    public function toFormattedString(): ?string
    {
        return $this->date?->format('Y-m-d');
    }

    public function getWeekOfYear(): ?int
    {
        return $this->date?->weekOfYear;
    }

    public function isSet(): bool
    {
        return $this->date !== null;
    }

    public function getCarbon(): ?Carbon
    {
        return $this->date;
    }

    public function jsonSerialize(): ?string
    {
        return $this->toFormattedString();
    }
}
