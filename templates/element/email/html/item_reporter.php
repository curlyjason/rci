<?php
/**
 * @var \App\Model\Entity\CustomersItem $completeCustomersItem
 * @var \Cake\View\View $this
 */

use App\Utilities\EntityAccessDecorator;
$completeCustomersItem->extra = ['one', 'two', 'three'];

$item = new EntityAccessDecorator($completeCustomersItem);
?>
<pre>
    <?= var_export($item->paths()) ?>
</pre>
<pre>
    <?= var_export($item->extract('item.name')) ?>
</pre>
<p><?= $item->get('item.name') ?></p>
