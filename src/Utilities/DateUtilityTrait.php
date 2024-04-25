<?php

namespace App\Utilities;

use Cake\I18n\DateTime;

/**
 * Encapsulate the tools to make dates
 *
 * Many queries and trigger states depend boundary
 * dates (such as 'the first of next month') and while
 * these are not too hard to create, this trait will
 * encapsulate them with simple names.
 */
trait DateUtilityTrait
{
    public function nextInventoryDate()
    {
        return (new DateTime())
            ->firstOfMonth()
            ->modify('first day of next month')
            ->format('Y-m-d 00:00:01');
    }
}
