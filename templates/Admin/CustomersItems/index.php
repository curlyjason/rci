<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 */
?>
<div class="customersItems index content">
    <?= $this->Html->link(__('New Customers Item'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Customers Items') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('quantity') ?></th>
                    <th><?= $this->Paginator->sort('target_quantity') ?></th>
                    <th><?= $this->Paginator->sort('next_inventory') ?></th>
                    <th><?= $this->Paginator->sort('customer_id') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customersItems as $customersItem): ?>
                <tr>
                    <td><?= $this->Number->format($customersItem->id) ?></td>
                    <td><?= $customersItem->quantity === null ? '' : $this->Number->format($customersItem->quantity) ?></td>
                    <td><?= $customersItem->target_quantity === null ? '' : $this->Number->format($customersItem->target_quantity) ?></td>
                    <td><?= h($customersItem->next_inventory) ?></td>
                    <td><?= $customersItem->hasValue('customer') ? $this->Html->link($customersItem->customer->name, ['controller' => 'Customers', 'action' => 'view', $customersItem->customer->id]) : '' ?></td>
                    <td><?= $customersItem->hasValue('item') ? $this->Html->link($customersItem->item->name, ['controller' => 'Items', 'action' => 'view', $customersItem->item->id]) : '' ?></td>
                    <td><?= h($customersItem->created) ?></td>
                    <td><?= h($customersItem->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $customersItem->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $customersItem->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $customersItem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customersItem->id)]) ?>
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
