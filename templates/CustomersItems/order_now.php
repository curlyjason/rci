<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 */
?>
<div class="customersItems index content">
    <?= $this->Html->link(__('New Customers Item'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Customers Items (Filter top customer and name them here)') ?></h3>
    <?= $this->Form->select('item', $customersItems, ['empty' => 'Choose an item']) ?>
</div>
