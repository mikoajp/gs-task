<?php

namespace Tests\Unit;

use App\Enums\MessageType;
use App\Services\MessageClassifier;
use Tests\TestCase;

class MessageClassifierTest extends TestCase
{
    private MessageClassifier $classifier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classifier = new MessageClassifier;
    }

    public function test_it_classifies_inspection_by_keyword(): void
    {
        $result = $this->classifier->classify('To jest przegląd techniczny');

        $this->assertEquals(MessageType::INSPECTION, $result);
    }

    public function test_it_classifies_failure_report_by_default(): void
    {
        $result = $this->classifier->classify('Awaria klimatyzacji');

        $this->assertEquals(MessageType::FAILURE_REPORT, $result);
    }

    public function test_it_is_case_insensitive(): void
    {
        $result = $this->classifier->classify('To jest PRZEGLĄD');

        $this->assertEquals(MessageType::INSPECTION, $result);
    }

    public function test_it_detects_inspection_in_middle_of_text(): void
    {
        $result = $this->classifier->classify('Proszę przyjechać na przegląd instalacji');

        $this->assertEquals(MessageType::INSPECTION, $result);
    }
}
