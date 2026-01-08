<?php

namespace App\Repositories;

use App\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class JsonMessageRepository implements MessageRepositoryInterface
{
    public function __construct(
        private readonly string $inspectionsFilePath = 'inspections.json',
        private readonly string $failureReportsFilePath = 'failure_reports.json',
        private readonly string $unprocessedMessagesFilePath = 'unprocessed_messages.json'
    ) {}

    public function saveInspections(array $inspections): string
    {
        $this->saveToFile($this->inspectionsFilePath, $inspections);
        Log::info('Inspections saved', ['file' => $this->inspectionsFilePath, 'count' => count($inspections)]);

        return $this->inspectionsFilePath;
    }

    public function saveFailureReports(array $failureReports): string
    {
        $this->saveToFile($this->failureReportsFilePath, $failureReports);
        Log::info('Failure reports saved', ['file' => $this->failureReportsFilePath, 'count' => count($failureReports)]);

        return $this->failureReportsFilePath;
    }

    public function saveUnprocessedMessages(array $unprocessedMessages): string
    {
        $this->saveToFile($this->unprocessedMessagesFilePath, $unprocessedMessages);
        Log::info('Unprocessed messages saved', ['file' => $this->unprocessedMessagesFilePath, 'count' => count($unprocessedMessages)]);

        return $this->unprocessedMessagesFilePath;
    }

    private function saveToFile(string $path, array $data): void
    {
        File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
