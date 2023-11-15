<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ItemsVendor> $itemsVendors
 */
?>
<div class="itemsVendors index content">
    <?= $this->Html->link(__('New Items Vendor'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Items Vendors') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('sku') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('vendor_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemsVendors as $itemsVendor): ?>
                <tr>
                    <td><?= $this->Number->format($itemsVendor->id) ?></td>
                    <td><?= h($itemsVendor->sku) ?></td>
                    <td><?= $itemsVendor->hasValue('item') ? $this->Html->link($itemsVendor->item->name, ['controller' => 'Items', 'action' => 'view', $itemsVendor->item->id]) : '' ?></td>
                    <td><?= $itemsVendor->hasValue('vendor') ? $this->Html->link($itemsVendor->vendor->name, ['controller' => 'Vendors', 'action' => 'view', $itemsVendor->vendor->id]) : '' ?></td>
                    <td><?= h($itemsVendor->created) ?></td>
                    <td><?= h($itemsVendor->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $itemsVendor->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $itemsVendor->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $itemsVendor->id], ['confirm' => __('Are you sure you want to delete # {0}?', $itemsVendor->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
