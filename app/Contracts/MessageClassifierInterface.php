<?php

namespace App\Contracts;

use App\Enums\MessageType;

interface MessageClassifierInterface
{
    /**
     * Classify message based on its description
     */
    public function classify(string $description): MessageType;
}
