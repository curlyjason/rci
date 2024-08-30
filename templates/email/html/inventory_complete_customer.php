<?php
/** @var \App\Utilities\CustomerInventoryStatusReporter $statusReporter
 * @var \Cake\View\View $this
 */

use App\Constants\CISRCon;

//var_dump($statusReporter);
?>
<table>
    <?= $this->Html->tableHeaders(['name', 'quantity', 'PAR', 'order_amount']) ?>
    <?php
    foreach ($statusReporter->getItems(CISRCon::COMPLETE) as $index => $completeCustomersItem) {
        echo $this->element('email/html/item_reporter', ['completeCustomersItem' => $completeCustomersItem]);
        }
    ?>
</table>
