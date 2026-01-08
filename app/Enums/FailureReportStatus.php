<?php

namespace App\Enums;

enum FailureReportStatus: string
{
    case NEW = 'nowy';
    case APPOINTMENT_SET = 'termin';

    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Determine status based on whether service visit date is set
     */
    public static function fromDate(?string $date): self
    {
        return $date !== null ? self::APPOINTMENT_SET : self::NEW;
    }
}
