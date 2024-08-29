<?php
/**
 * @var \App\Model\Entity\CustomersItem $completeCustomersItem
 * @var \Cake\View\View $this
 */

use App\Utilities\EntityAccessDecorator;
use Cake\Utility\Text;

$item = new EntityAccessDecorator($completeCustomersItem);
$cells = $item->collate('item.name', 'quantity', 'target_quantity', 'order_amount');

?>
<!--<pre>-->
<!--    --><?php //= var_export($item->paths(), true) ?>
<!--</pre>-->
<p><?= Text::toList($cells) ?></p>
