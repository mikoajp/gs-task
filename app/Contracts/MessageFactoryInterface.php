<?php

namespace App\Contracts;

use App\DTOs\FailureReport;
use App\DTOs\Inspection;

interface MessageFactoryInterface
{
    /**
     * Create inspection from raw message data
     */
    public function createInspection(array $data): Inspection;

    /**
     * Create failure report from raw message data
     */
    public function createFailureReport(array $data): FailureReport;
}
