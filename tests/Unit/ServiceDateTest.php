<?php

namespace Tests\Unit;

use App\ValueObjects\ServiceDate;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ServiceDateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Log::shouldReceive('warning')->andReturnNull();
    }

    public function test_it_parses_valid_date(): void
    {
        $date = ServiceDate::fromString('2023-10-15');

        $this->assertTrue($date->isSet());
        $this->assertEquals('2023-10-15', $date->toFormattedString());
        $this->assertEquals(41, $date->getWeekOfYear());
    }

    public function test_it_handles_empty_string(): void
    {
        $date = ServiceDate::fromString('');

        $this->assertFalse($date->isSet());
        $this->assertNull($date->toFormattedString());
    }

    public function test_it_handles_null(): void
    {
        $date = ServiceDate::fromString(null);

        $this->assertFalse($date->isSet());
        $this->assertNull($date->toFormattedString());
    }

    public function test_it_handles_invalid_date(): void
    {
        $date = ServiceDate::fromString('invalid-date');

        $this->assertFalse($date->isSet());
        $this->assertNull($date->toFormattedString());
    }

    public function test_it_parses_datetime_string(): void
    {
        $date = ServiceDate::fromString('2023-10-15 14:30:00');

        $this->assertTrue($date->isSet());
        $this->assertEquals('2023-10-15', $date->toFormattedString());
    }
}
