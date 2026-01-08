<?php

namespace App\Enums;

enum MessageType: string
{
    case INSPECTION = 'przegląd';
    case FAILURE_REPORT = 'zgłoszenie awarii';

    public function getLabel(): string
    {
        return $this->value;
    }
}
