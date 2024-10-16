<?php

namespace App\Test\TestCase\Utilities;

use App\Utilities\DateUtilityTrait;
use Cake\I18n\FrozenTime;

class DateUtilityTraitTest extends \Cake\TestSuite\TestCase
{
    /**
     * Dummy class to 'use' the Trait for testing
     * Defined at the bottom of this file
     *
     * @var SUT $SUT
     */

    protected $SUT;

    protected function setUp(): void
    {
        parent::setUp();
        $this->SUT = new SUT(); //class using the DateUtilityTrait
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_detect24HourOldDate()
    {
        $dateString = (new FrozenTime(time()))->modify('-1 day');

        $this->assertTrue($this->SUT->atLeastADayOld($dateString));
    }

    public function test_detectLessThan24HoursOld()
    {
        $dateString = (new FrozenTime(time()))->modify('-15 hours');

        $this->assertFalse($this->SUT->atLeastADayOld($dateString));
    }

    public function test_duringLastCycle_true ()
    {
        $dateToTest = (new \DateTime())
            ->modify('-1 month')
            ->format('Y-m-d h:i:s');

        $this->assertTrue($this->SUT->duringLastCycle($dateToTest));
    }

    public function test_duringLastCycle_false ()
    {
        $dateToTest = (new \DateTime())
            ->format('Y-m-d h:i:s');

        $this->assertFalse($this->SUT->duringLastCycle($dateToTest));

        $dateToTest = $this->SUT->thisMonthsInventoryDate();

        $this->assertFalse($this->SUT->duringLastCycle($dateToTest));
    }

    public function test_firstDayOfCycle_true()
    {
        $dateToTest = (new \DateTime())
            ->format('Y-m-01 h:i:s');

        $this->assertTrue($this->SUT->firstDayOfCycle($dateToTest));

        $dateToTest = $this->SUT->thisMonthsInventoryDate();

        $this->assertTrue($this->SUT->firstDayOfCycle($dateToTest));
    }

    public function test_firstDayOfCycle_false()
    {
        $dateToTest = $this->SUT->thisMonthsInventoryDate(false)
            ->modify('+1 day')
            ->format($this->SUT::DATE_FORMAT_SQL);

        $this->assertFalse($this->SUT->firstDayOfCycle($dateToTest));

    }

}

class SUT {
    use DateUtilityTrait;
}
