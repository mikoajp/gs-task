<?php

namespace Tests\Unit;

use App\DTOs\FailureReport;
use App\DTOs\Inspection;
use App\Enums\InspectionStatus;
use App\Enums\Priority;
use App\Services\MessageClassifier;
use App\Services\MessageFactory;
use App\Services\MessageProcessor;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MessageProcessorTest extends TestCase
{
    private MessageProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Log facade to avoid facade errors in unit tests
        Log::shouldReceive('info')->andReturnNull();
        Log::shouldReceive('warning')->andReturnNull();
        Log::shouldReceive('debug')->andReturnNull();

        $classifier = new MessageClassifier;
        $factory = new MessageFactory;
        $this->processor = new MessageProcessor($classifier, $factory);
    }

    public function test_it_classifies_inspection_based_on_description(): void
    {
        $data = [
            ['description' => 'To jest przegląd techniczny', 'dueDate' => '2023-01-01', 'number' => 1],
        ];

        $result = $this->processor->process($data);

        $this->assertCount(1, $result->inspections);
        $this->assertCount(0, $result->failureReports);
        $this->assertInstanceOf(Inspection::class, $result->inspections[0]);
        $this->assertEquals(InspectionStatus::SCHEDULED, $result->inspections[0]->status);
    }

    public function test_it_classifies_failure_report_by_default(): void
    {
        $data = [
            ['description' => 'Coś się zepsuło', 'dueDate' => '2023-01-01', 'number' => 1],
        ];

        $result = $this->processor->process($data);

        $this->assertCount(0, $result->inspections);
        $this->assertCount(1, $result->failureReports);
        $this->assertInstanceOf(FailureReport::class, $result->failureReports[0]);
    }

    public function test_it_assigns_critical_priority_for_very_urgent(): void
    {
        $data = [
            ['description' => 'To jest bardzo pilne zgłoszenie', 'dueDate' => null, 'number' => 1],
        ];

        $result = $this->processor->process($data);

        $this->assertEquals(Priority::CRITICAL, $result->failureReports[0]->priority);
    }

    public function test_it_assigns_high_priority_for_urgent(): void
    {
        $data = [
            ['description' => 'To jest pilne zgłoszenie', 'dueDate' => null, 'number' => 1],
        ];

        $result = $this->processor->process($data);

        $this->assertEquals(Priority::HIGH, $result->failureReports[0]->priority);
    }

    public function test_it_assigns_normal_priority_by_default(): void
    {
        $data = [
            ['description' => 'Zwykła awaria', 'dueDate' => null, 'number' => 1],
        ];

        $result = $this->processor->process($data);

        $this->assertEquals(Priority::NORMAL, $result->failureReports[0]->priority);
    }

    public function test_it_skips_duplicates(): void
    {
        $data = [
            ['description' => 'Awaria 1', 'dueDate' => null, 'number' => 1],
            ['description' => 'Awaria 1', 'dueDate' => null, 'number' => 2],
        ];

        $result = $this->processor->process($data);

        $this->assertCount(1, $result->failureReports);
        $this->assertCount(1, $result->skipped);
        $this->assertEquals('Duplicate description', $result->skipped[0]['reason']);
    }

    public function test_it_parses_dates_correctly(): void
    {
        $data = [
            ['description' => 'Przegląd', 'dueDate' => '2023-10-15 10:00:00', 'number' => 1],
        ];

        $result = $this->processor->process($data);
        $inspection = $result->inspections[0];

        $this->assertEquals('2023-10-15', $inspection->inspectionDate->toFormattedString());
        // Week of year for 2023-10-15 is 41
        $this->assertEquals(41, $inspection->inspectionDate->getWeekOfYear());
        $this->assertEquals(InspectionStatus::SCHEDULED, $inspection->status);
    }

    public function test_it_handles_missing_dates(): void
    {
        $data = [
            ['description' => 'Przegląd', 'dueDate' => null, 'number' => 1],
        ];

        $result = $this->processor->process($data);
        $inspection = $result->inspections[0];

        $this->assertNull($inspection->inspectionDate->toFormattedString());
        $this->assertEquals(InspectionStatus::NEW, $inspection->status);
    }

    public function test_it_returns_unprocessed_messages(): void
    {
        $data = [
            ['description' => 'Valid message', 'dueDate' => null, 'number' => 1],
            ['description' => '', 'dueDate' => null, 'number' => 2],
            ['description' => 'Valid message', 'dueDate' => null, 'number' => 3], // duplicate
        ];

        $result = $this->processor->process($data);

        $this->assertCount(2, $result->unprocessed);
        $this->assertEquals(2, $result->unprocessed[0]['number']);
        $this->assertEquals(3, $result->unprocessed[1]['number']);
    }

    public function test_it_detects_high_priority_variants(): void
    {
        $testCases = [
            ['description' => 'To jest pilne', 'expected' => Priority::HIGH],
            ['description' => 'To jest pilna sprawa', 'expected' => Priority::HIGH],
            ['description' => 'To jest pilny problem', 'expected' => Priority::HIGH],
            ['description' => 'To jest dość pilna sprawa', 'expected' => Priority::HIGH],
            ['description' => 'To jest dosć pilna sprawa', 'expected' => Priority::HIGH],
        ];

        foreach ($testCases as $testCase) {
            $data = [
                ['description' => $testCase['description'], 'dueDate' => null, 'number' => 1],
            ];

            $result = $this->processor->process($data);

            $this->assertEquals($testCase['expected'], $result->failureReports[0]->priority,
                "Failed for: {$testCase['description']}");

            // Reset processor for next test
            $classifier = new MessageClassifier;
            $factory = new MessageFactory;
            $this->processor = new MessageProcessor($classifier, $factory);
        }
    }

    public function test_unprocessed_array_key_exists(): void
    {
        $data = [
            ['description' => 'Valid message', 'dueDate' => null, 'number' => 1],
        ];

        $result = $this->processor->process($data);

        $this->assertIsArray($result->unprocessed);
        $this->assertEmpty($result->unprocessed);
    }
}
