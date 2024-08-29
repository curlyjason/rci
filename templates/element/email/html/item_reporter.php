<?php
/**
 * @var \App\Model\Entity\CustomersItem $completeCustomersItem
 * @var \Cake\View\View $this
 */

use App\Utilities\EntityAccessDecorator;
$completeCustomersItem->extra = [
    ['a' => 'apple', 'b' => 'banana'],
    ['a' => 'apple', 'b' => 'banana'],
    ['a' => 'apple', 'b' => 'banana']
];

$item = new EntityAccessDecorator($completeCustomersItem);
?>
<pre>
    <?= var_export($item->paths(), true) ?>
</pre>
<pre>
    <?= var_export($item->collate('item.name', 'quantity', 'target_quantity', 'order_amount'), true) ?>
</pre>
<pre>
    <?= var_export($item->extract('extra.{n}.a'), true) ?>
</pre>
<p><?= $item->get('item.name') ?></p>
