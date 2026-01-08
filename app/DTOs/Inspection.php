<?php

namespace App\DTOs;

use App\Enums\InspectionStatus;
use App\Enums\MessageType;
use App\ValueObjects\PhoneNumber;
use App\ValueObjects\ServiceDate;
use Carbon\Carbon;
use JsonSerializable;

readonly class Inspection implements JsonSerializable
{
    public function __construct(
        public string $description,
        public ServiceDate $inspectionDate,
        public InspectionStatus $status,
        public PhoneNumber $contactPhone,
        public MessageType $type,
        public string $recommendations = '',
        public ?Carbon $createdAt = null
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'opis' => $this->description,
            'typ' => $this->type->value,
            'data przeglądu' => $this->inspectionDate->toFormattedString(),
            'tydzień w roku daty przeglądu' => $this->inspectionDate->getWeekOfYear(),
            'status' => $this->status->value,
            'zalecenia dalszej obsługi po przeglądzie' => $this->recommendations,
            'numer telefonu osoby do kontaktu po stronie klienta' => $this->contactPhone->getValue(),
            'data utworzenia' => ($this->createdAt ?? now())->toDateTimeString(),
        ];
    }
}
