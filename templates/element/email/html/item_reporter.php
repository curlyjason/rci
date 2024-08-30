<?php
/**
 * @var \App\Model\Entity\CustomersItem $completeCustomersItem
 * @var \Cake\View\View $this
 */

use App\Utilities\EntityAccessDecorator;

$item = new EntityAccessDecorator($completeCustomersItem);
$cells = $item->collate('item.name', 'quantity', 'target_quantity', 'order_amount');

?>
<?= $this->Html->tableCells($cells) ?>
