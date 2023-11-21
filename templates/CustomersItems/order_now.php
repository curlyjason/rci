<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 */

$this->append('script', $this->Html->script('order_tools.js'));

?>
<div class="customersItems index content">
    <?= $this->element('new_item_button') ?>
    <h3><?= __('Customers Items (Filter top customer and name them here)') ?></h3>
    <?= $this->Form->select('item', $customersItems, ['empty' => 'Choose an item']) ?>
</div>
