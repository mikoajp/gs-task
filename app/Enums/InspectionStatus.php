<?php

namespace App\Enums;

enum InspectionStatus: string
{
    case NEW = 'nowy';
    case SCHEDULED = 'zaplanowano';

    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Determine status based on whether inspection date is set
     */
    public static function fromDate(?string $date): self
    {
        return $date !== null ? self::SCHEDULED : self::NEW;
    }
}
