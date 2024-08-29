<?php
/** @var \App\Utilities\CustomerInventoryStatusReporter $statusReporter
 * @var \Cake\View\View $this
 */

use App\Constants\CISRCon;

//var_dump($statusReporter);

?>
<!--<pre>-->
<!--    --><?php //debug($statusReporter->getItems(CISRCon::COMPLETE)) ?>
    <?php
//        debug($statusReporter->getItems(CISRCon::COMPLETE));
    foreach ($statusReporter->getItems(CISRCon::COMPLETE) as $index => $completeCustomersItem) {
        echo $this->element('email/html/item_reporter', ['completeCustomersItem' => $completeCustomersItem]);
        }
    ?>
<!--</pre>-->
