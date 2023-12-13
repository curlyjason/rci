<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomersItem $customersItem
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Customers Item'), ['action' => 'edit', $customersItem->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Customers Item'), ['action' => 'delete', $customersItem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customersItem->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Customers Items'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Customers Item'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="customersItems view content">
            <h3><?= h($customersItem->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Customer') ?></th>
                    <td><?= $customersItem->hasValue('customer') ? $this->Html->link($customersItem->customer->name, ['controller' => 'Customers', 'action' => 'view', $customersItem->customer->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Item') ?></th>
                    <td><?= $customersItem->hasValue('item') ? $this->Html->link($customersItem->item->name, ['controller' => 'Items', 'action' => 'view', $customersItem->item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($customersItem->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Quantity') ?></th>
                    <td><?= $customersItem->quantity === null ? '' : $this->Number->format($customersItem->quantity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Target Quantity') ?></th>
                    <td><?= $customersItem->target_quantity === null ? '' : $this->Number->format($customersItem->target_quantity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Next Inventory') ?></th>
                    <td><?= h($customersItem->next_inventory) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($customersItem->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($customersItem->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
