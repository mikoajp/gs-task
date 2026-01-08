<?php

namespace App\ValueObjects;

use JsonSerializable;

readonly class PhoneNumber implements JsonSerializable
{
    private function __construct(
        private ?string $value
    ) {}

    public static function fromString(?string $phone): self
    {
        if (empty($phone)) {
            return new self(null);
        }

        return new self(trim($phone));
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    public function jsonSerialize(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
