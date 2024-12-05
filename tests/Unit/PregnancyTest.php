<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Pregnancy;

class PregnancyTest extends TestCase
{
    /**
     * Test the days_left accessor with a past termin_date.
     */
    public function testDaysLeftWithPastDate()
    {
        // Mock the current date
        Carbon::setTestNow('2024-11-30');

        // Create a Pregnancy instance in memory
        $pregnancy = new Pregnancy(['termin_date' => '2024-11-00']);

        // Assert the days_left is 0 (no negative values)
        $this->assertEquals(-30, $pregnancy->getDaysLeftAttribute());
    }

    /**
     * Test the days_left accessor with today's date.
     */
    public function testDaysLeftWithTodayDate()
    {
        // Mock the current date
        Carbon::setTestNow('2024-12-05');

        // Create a Pregnancy instance in memory
        $pregnancy = new Pregnancy(['termin_date' => '2024-12-05']);

        // Assert the days_left is 0 (termin_date is today)
        $this->assertEquals(0, $pregnancy->getDaysLeftAttribute());
    }

    /**
     * Test the days_left accessor with a null termin_date.
     */
    public function testDaysLeftWithNullTerminDate()
    {
        // Create a Pregnancy instance in memory with null termin_date
        $pregnancy = new Pregnancy(['termin_date' => null]);

        // Assert the days_left is null
        $this->assertNull($pregnancy->getDaysLeftAttribute());
    }
}
