<?php

/**
 * @var \App\Utilities\CustomerInventoryStatusReporter $inventoryReporter
 * @var \App\Model\Entity\User $user
 **/

use App\Constants\CISRCon;
use App\Utilities\CustomerInventoryStatusReporter;

osd($inventoryReporter->getItems(\App\Constants\CISRCon::COMPLETE), 'Complete Items');
osd($inventoryReporter->getItems(CISRCon::INCOMPLETE), 'Incomplete Items');
osd($inventoryReporter->inventoryComplete(), 'Inventory Complete?');
osd($inventoryReporter->getNewOrderPost($user));
