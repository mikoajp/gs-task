<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\MessageClassifierInterface;
use App\Contracts\MessageFactoryInterface;
use App\DTOs\ProcessingResult;
use App\Enums\MessageType;
use Illuminate\Support\Facades\Log;

class MessageProcessor
{
    private array $processedDescriptions = [];

    private array $inspections = [];

    private array $failureReports = [];

    private array $skippedMessages = [];

    private array $unprocessedMessages = [];

    public function __construct(
        private readonly MessageClassifierInterface $classifier,
        private readonly MessageFactoryInterface $factory
    ) {}

    /**
     * Process array of raw messages
     */
    public function process(array $messages): ProcessingResult
    {
        $this->reset();

        Log::info('Starting message processing', ['total_messages' => count($messages)]);

        foreach ($messages as $message) {
            $this->processMessage($message);
        }

        Log::info('Message processing completed', [
            'inspections_created' => count($this->inspections),
            'failure_reports_created' => count($this->failureReports),
            'messages_skipped' => count($this->skippedMessages),
        ]);

        return new ProcessingResult(
            inspections: $this->inspections,
            failureReports: $this->failureReports,
            skipped: $this->skippedMessages,
            unprocessed: $this->unprocessedMessages
        );
    }

    private function processMessage(array $message): void
    {
        $messageNumber = $message['number'] ?? 'unknown';
        $description = trim($message['description'] ?? '');

        // Validate description
        if ($description === '') {
            $this->skipMessage($message, $messageNumber, 'Empty description');

            return;
        }

        // Check for duplicates
        if ($this->isDuplicate($description)) {
            $this->skipMessage($message, $messageNumber, 'Duplicate description');

            return;
        }

        // Add to processed list using hash for O(1) lookup
        $this->processedDescriptions[md5($description)] = true;

        // Classify and create appropriate entity
        $messageType = $this->classifier->classify($description);

        Log::info('Classified message', [
            'message_number' => $messageNumber,
            'type' => $messageType->value,
        ]);

        match ($messageType) {
            MessageType::INSPECTION => $this->inspections[] = $this->factory->createInspection($message),
            MessageType::FAILURE_REPORT => $this->failureReports[] = $this->factory->createFailureReport($message),
        };
    }

    private function isDuplicate(string $description): bool
    {
        return isset($this->processedDescriptions[md5($description)]);
    }

    private function skipMessage(array $message, int|string $messageNumber, string $reason): void
    {
        Log::warning('Skipping message', [
            'message_number' => $messageNumber,
            'reason' => $reason,
        ]);

        $this->skippedMessages[] = [
            'number' => $messageNumber,
            'reason' => $reason,
        ];

        $this->unprocessedMessages[] = $message;
    }

    private function reset(): void
    {
        $this->processedDescriptions = [];
        $this->inspections = [];
        $this->failureReports = [];
        $this->skippedMessages = [];
        $this->unprocessedMessages = [];
    }
}
