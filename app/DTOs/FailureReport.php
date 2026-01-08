<?php

namespace App\DTOs;

use App\Enums\FailureReportStatus;
use App\Enums\MessageType;
use App\Enums\Priority;
use App\ValueObjects\PhoneNumber;
use App\ValueObjects\ServiceDate;
use Carbon\Carbon;
use JsonSerializable;

readonly class FailureReport implements JsonSerializable
{
    public function __construct(
        public string $description,
        public Priority $priority,
        public ServiceDate $serviceVisitDate,
        public FailureReportStatus $status,
        public PhoneNumber $contactPhone,
        public MessageType $type,
        public string $serviceNotes = '',
        public ?Carbon $createdAt = null
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'opis' => $this->description,
            'typ' => $this->type->value,
            'priorytet' => $this->priority->value,
            'termin wizyty serwisu' => $this->serviceVisitDate->toFormattedString(),
            'status' => $this->status->value,
            'uwagi serwisu' => $this->serviceNotes,
            'numer telefonu osoby do kontaktu po stronie klienta' => $this->contactPhone->getValue(),
            'data utworzenia' => ($this->createdAt ?? now())->toDateTimeString(),
        ];
    }
}
