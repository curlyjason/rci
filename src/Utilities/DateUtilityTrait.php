<?php

namespace App\Utilities;

use Cake\I18n\DateTime;

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
 */
trait DateUtilityTrait
{
    public const DATE_FORMAT_SQL = 'Y-m-d 00:00:01';

    public const DATE_FORMAT_YYYY_MM_DD = 'Y-m-d';

    public function nextMonthsInventoryDate($format = self::DATE_FORMAT_SQL): string
    {
        return (new DateTime())
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
}
