<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 */

$this->append('script', $this->Html->script('inventory_tools.js'));

?>
<div class="customersItems index content">
    <?= $this->element('new_item_button') ?>
    <h3><?= __('Customers Items (Filter top customer and name them here)') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('target_quantity') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('quantity') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customersItems as $customersItem): ?>
                <tr>
                    <td><?= $customersItem->target_quantity === null ? '' : $this->Number->format($customersItem->target_quantity) ?></td>
                    <td><?= $customersItem->hasValue('item') ? $this->Html->link($customersItem->item->name, ['controller' => 'Items', 'action' => 'view', $customersItem->item->id]) : '' ?></td>
                    <td><?= $this->Form->postLink($this->Number->format($customersItem->quantity),'take-inventory',[]) ?></td>
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
