<?php

namespace App\Services;

use App\Contracts\MessageFactoryInterface;
use App\DTOs\FailureReport;
use App\DTOs\Inspection;
use App\Enums\FailureReportStatus;
use App\Enums\InspectionStatus;
use App\Enums\MessageType;
use App\Enums\Priority;
use App\ValueObjects\PhoneNumber;
use App\ValueObjects\ServiceDate;
use Illuminate\Support\Facades\Log;

class MessageFactory implements MessageFactoryInterface
{
    public function createInspection(array $data): Inspection
    {
        $serviceDate = ServiceDate::fromString($data['dueDate'] ?? null);
        $status = InspectionStatus::fromDate($serviceDate->toFormattedString());

        Log::debug('Creating inspection', [
            'message_number' => $data['number'] ?? 'unknown',
            'status' => $status->value,
            'has_date' => $serviceDate->isSet(),
        ]);

        return new Inspection(
            description: trim($data['description']),
            inspectionDate: $serviceDate,
            status: $status,
            contactPhone: PhoneNumber::fromString($data['phone'] ?? null),
            type: MessageType::INSPECTION
        );
    }

    public function createFailureReport(array $data): FailureReport
    {
        $description = trim($data['description']);
        $serviceDate = ServiceDate::fromString($data['dueDate'] ?? null);
        $status = FailureReportStatus::fromDate($serviceDate->toFormattedString());
        $priority = Priority::fromDescription($description);

        Log::debug('Creating failure report', [
            'message_number' => $data['number'] ?? 'unknown',
            'priority' => $priority->value,
            'status' => $status->value,
            'has_date' => $serviceDate->isSet(),
        ]);

        return new FailureReport(
            description: $description,
            priority: $priority,
            serviceVisitDate: $serviceDate,
            status: $status,
            contactPhone: PhoneNumber::fromString($data['phone'] ?? null),
            type: MessageType::FAILURE_REPORT
        );
    }
}
