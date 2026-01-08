<?php

namespace App\Console\Commands;

use App\Services\MessageProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProcessMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-messages {file : The path to the source JSON file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recruitment task messages into Inspections and Failure Reports';

    /**
     * Execute the console command.
     */
    public function handle(
        MessageProcessor $processor,
        \App\Contracts\MessageRepositoryInterface $repository
    ): int {
        $filePath = $this->argument('file');

        if (! File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            \Log::error('File not found', ['file_path' => $filePath]);

            return 1;
        }

        $content = File::get($filePath);
        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON format: '.json_last_error_msg());
            \Log::error('Invalid JSON format', ['error' => json_last_error_msg(), 'file_path' => $filePath]);

            return 1;
        }

        if (! is_array($json)) {
            $this->error('JSON root must be an array.');
            \Log::error('JSON root must be an array', ['file_path' => $filePath]);

            return 1;
        }

        $this->info("Processing messages from {$filePath}...");
        \Log::info('Starting command execution', ['file_path' => $filePath, 'total_messages' => count($json)]);

        $result = $processor->process($json);

        // Save files using repository
        $inspectionsFile = $repository->saveInspections($result->inspections);
        $failuresFile = $repository->saveFailureReports($result->failureReports);
        $unprocessedFile = $repository->saveUnprocessedMessages($result->unprocessed);

        $this->info("Successfully created {$inspectionsFile}, {$failuresFile} and {$unprocessedFile}.");

        // Summary
        $totalProcessed = count($json);
        $totalInspections = count($result->inspections);
        $totalFailures = count($result->failureReports);
        $totalSkipped = $result->getTotalSkipped();

        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Messages Found', $totalProcessed],
                ['Inspections Created', $totalInspections],
                ['Failure Reports Created', $totalFailures],
                ['Skipped/Unprocessed', $totalSkipped],
            ]
        );

        if ($totalSkipped > 0) {
            $this->newLine();
            $this->info('Skipped Messages Details:');
            $this->table(
                ['Message Number', 'Reason'],
                $result->skipped
            );
        }

        \Log::info('Command execution completed successfully');

        return 0;
    }
}
