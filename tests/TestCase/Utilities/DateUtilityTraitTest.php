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

        $this->assertTrue($this->SUT->aboutADayOld($dateString));
    }

    public function test_detectLessThan24HoursOld()
    {
        $dateString = (new FrozenTime(time()))->modify('-15 hours');

        $this->assertFalse($this->SUT->aboutADayOld($dateString));
    }

    public function test_duringLastCycle_true ()
    {
        $dateToTest = (new \DateTime())
            ->modify('-1 month')
            ->format('Y-m-d h:i:s');
        debug($dateToTest);

        $this->assertTrue($this->SUT->duringLastCycle($dateToTest));
    }

}

class SUT {
    use DateUtilityTrait;
}
