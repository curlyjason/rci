<?php

/**
 * @var \App\Utilities\CustomerInventoryStatusReporter $inventoryReporter
 * @var \App\Model\Entity\User $user
 **/

use App\Utilities\CustomerInventoryStatusReporter;

osd($inventoryReporter->getItems(CustomerInventoryStatusReporter::COMPLETE), 'Complete Items');
osd($inventoryReporter->getItems(CustomerInventoryStatusReporter::INCOMPLETE), 'Incomplete Items');
osd($inventoryReporter->inventoryComplete(), 'Inventory Complete?');
osd($inventoryReporter->getNewOrderPost($user));
