<?php

namespace Tests\Unit;

use App\Enums\Priority;
use Tests\TestCase;

class PriorityEnumTest extends TestCase
{
    public function test_it_detects_critical_priority_from_description(): void
    {
        $this->assertEquals(Priority::CRITICAL, Priority::fromDescription('To jest bardzo pilne'));
        $this->assertEquals(Priority::CRITICAL, Priority::fromDescription('Bardzo pilna sprawa'));
    }

    public function test_it_detects_high_priority_from_description(): void
    {
        $this->assertEquals(Priority::HIGH, Priority::fromDescription('To jest pilne'));
        $this->assertEquals(Priority::HIGH, Priority::fromDescription('Pilna sprawa'));
        $this->assertEquals(Priority::HIGH, Priority::fromDescription('Pilny problem'));
        $this->assertEquals(Priority::HIGH, Priority::fromDescription('Dość pilna sprawa'));
        $this->assertEquals(Priority::HIGH, Priority::fromDescription('Dosć pilna sprawa'));
    }

    public function test_it_detects_normal_priority_by_default(): void
    {
        $this->assertEquals(Priority::NORMAL, Priority::fromDescription('Zwykła awaria'));
        $this->assertEquals(Priority::NORMAL, Priority::fromDescription('Coś się zepsuło'));
    }

    public function test_it_is_case_insensitive(): void
    {
        $this->assertEquals(Priority::CRITICAL, Priority::fromDescription('BARDZO PILNE'));
        $this->assertEquals(Priority::HIGH, Priority::fromDescription('PILNE'));
    }

    public function test_critical_takes_precedence_over_high(): void
    {
        // If description contains both "bardzo pilne" and "pilne", it should be critical
        $this->assertEquals(Priority::CRITICAL, Priority::fromDescription('To jest bardzo pilne i pilne'));
    }
}
