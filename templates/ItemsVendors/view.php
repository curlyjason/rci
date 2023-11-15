<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ItemsVendor $itemsVendor
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Items Vendor'), ['action' => 'edit', $itemsVendor->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Items Vendor'), ['action' => 'delete', $itemsVendor->id], ['confirm' => __('Are you sure you want to delete # {0}?', $itemsVendor->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Items Vendors'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Items Vendor'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="itemsVendors view content">
            <h3><?= h($itemsVendor->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Sku') ?></th>
                    <td><?= h($itemsVendor->sku) ?></td>
                </tr>
                <tr>
                    <th><?= __('Item') ?></th>
                    <td><?= $itemsVendor->hasValue('item') ? $this->Html->link($itemsVendor->item->name, ['controller' => 'Items', 'action' => 'view', $itemsVendor->item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Vendor') ?></th>
                    <td><?= $itemsVendor->hasValue('vendor') ? $this->Html->link($itemsVendor->vendor->name, ['controller' => 'Vendors', 'action' => 'view', $itemsVendor->vendor->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($itemsVendor->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($itemsVendor->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($itemsVendor->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
