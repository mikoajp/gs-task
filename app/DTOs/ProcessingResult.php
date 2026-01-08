<?php

namespace App\DTOs;

readonly class ProcessingResult
{
    /**
     * @param  Inspection[]  $inspections
     * @param  FailureReport[]  $failureReports
     * @param  array<array{number: int|string, reason: string}>  $skipped
     * @param  array<array>  $unprocessed
     */
    public function __construct(
        public array $inspections,
        public array $failureReports,
        public array $skipped,
        public array $unprocessed
    ) {}

    public function toArray(): array
    {
        return [
            'inspections' => $this->inspections,
            'failure_reports' => $this->failureReports,
            'skipped' => $this->skipped,
            'unprocessed' => $this->unprocessed,
        ];
    }

    public function getTotalProcessed(): int
    {
        return count($this->inspections) + count($this->failureReports);
    }

    public function getTotalSkipped(): int
    {
        return count($this->skipped);
    }
}
