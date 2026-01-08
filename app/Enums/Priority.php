<?php

namespace App\Enums;

enum Priority: string
{
    case CRITICAL = 'krytyczny';
    case HIGH = 'wysoki';
    case NORMAL = 'normalny';

    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Determine priority based on description keywords
     */
    public static function fromDescription(string $description): self
    {
        $lowerDescription = mb_strtolower($description);

        // Critical priority keywords
        if (str_contains($lowerDescription, 'bardzo pilne') ||
            str_contains($lowerDescription, 'bardzo pilna')) {
            return self::CRITICAL;
        }

        // High priority keywords
        $highPriorityKeywords = ['pilne', 'pilna', 'pilny', 'dość pilna', 'dosć pilna'];
        foreach ($highPriorityKeywords as $keyword) {
            if (str_contains($lowerDescription, $keyword)) {
                return self::HIGH;
            }
        }

        return self::NORMAL;
    }
}
