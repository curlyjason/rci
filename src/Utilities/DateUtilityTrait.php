<?php

namespace App\Utilities;

use Cake\I18n\DateTime;
use Cake\I18n\FrozenTime;

/**
 * Encapsulate the tools to make dates
 *
 * Many queries and trigger states use boundary
 * dates (such as 'the first of next month') and while
 * these are not too hard to create, this trait will
 * encapsulate them with simple names.
 *
 * @todo these methods could get args with defaults
 *      so customers could get something other than
 *      the-first-of-the-month as an inventory day
 * @todo THIS MUST BECOME POLYMORPHIC
 *      so that customers can have different trigger dates
 *
 * One way to make this polymorphic:
 * Make this a static class rather than a trait.
 * Customers that need a new rule just need to substitute
 * their named class. The class name can be based on
 * the Customer->name if we're can tolerate the
 * possibility of having duplicate classes (more than
 * one customer that triggers on the 15th of the month).
 * Otherwise, we can use a field value on Customer to
 * hold null or the name of the rule-class.
 */
trait DateUtilityTrait
{
    public const DATE_FORMAT_SQL = 'Y-m-d 00:00:01';

    public const DATE_FORMAT_YYYY_MM_DD = 'Y-m-d';

    public function nextMonthsInventoryDate($format = self::DATE_FORMAT_SQL): string
    {
        return /*$this->lookForSpecialRule()
            ? $this->propertyThatGotSet
            :*/  (new DateTime())
                ->firstOfMonth()
                ->modify('first day of next month')
                ->format($format);
    }

    public function thisMonthsInventoryDate($format = self::DATE_FORMAT_SQL): string
    {
        return (new DateTime())
            ->firstOfMonth()
            ->format($format);
    }

    public function twentyfourHoursAgo(): DateTime
    {
        $datetime = DateTime::now();
        return $datetime->modify('-1 day');
    }

    /**
     * Is the date 20 or more hours ago
     *
     * @param $dateToCheck
     * @return bool
     */
    public function aboutADayOld($dateToCheck)
    {
        return new FrozenTime($dateToCheck)
            <
            (new FrozenTime(time()))
                ->modify('-20 hours');
    }
}
