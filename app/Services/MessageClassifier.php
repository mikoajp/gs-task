<?php

namespace App\Services;

use App\Contracts\MessageClassifierInterface;
use App\Enums\MessageType;

class MessageClassifier implements MessageClassifierInterface
{
    private const INSPECTION_KEYWORDS = ['przegląd'];

    public function classify(string $description): MessageType
    {
        $lowerDescription = mb_strtolower($description);

        foreach (self::INSPECTION_KEYWORDS as $keyword) {
            if (str_contains($lowerDescription, $keyword)) {
                return MessageType::INSPECTION;
            }
        }

        return MessageType::FAILURE_REPORT;
    }
}
