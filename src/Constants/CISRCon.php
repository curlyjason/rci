<?php

namespace App\Constants;

/**
 * Constants for **`CustomerInventoryStatusReporter`**
 *
 * I made this to make the names shorter (CISRCon::*NAME*)
 * And I put this in with all the other constants
 * (even though this only serves tests) because
 *      - there is no security risk in the location or data
 *      - I didn't want another directory in Tests
 *      - it feels like we may need general use constants later
 */
class CISRCon
{
    //<editor-fold desc="TESTING CONSTANTS">
    const PARTIALLY_COMPLETE = 'partial';
    const ONE_EMAIL = 'oneEmail';
    const MULTI_EMAIL = 'multiEmail';
    const NO_EMAIL = 'noEmail';
    //</editor-fold>
    //<editor-fold desc="DATA-STRUCTURE CONSTANTS">
    public const COMPLETE = '_completeItems';
    public const INCOMPLETE = '_incompleteItems';
    /**
     * none required yet
     */
    //</editor-fold>
}
