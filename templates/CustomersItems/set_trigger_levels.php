<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 */

$this->append('script', $this->Html->script('trigger_tools.js'));

?>
<div class="customersItems index content">
    <?= $this->Html->link(__('New Customers Item'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Customers Items (Filter top customer and name them here)') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('quantity') ?></th>
                    <th><?= $this->Paginator->sort('target_quantity') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customersItems as $customersItem): ?>
                <tr>
                    <td><?= $customersItem->quantity === null ? '' : $this->Number->format($customersItem->quantity) ?></td>
                    <td><?= $customersItem->hasValue('item') ? $this->Html->link($customersItem->item->name, ['controller' => 'Items', 'action' => 'view', $customersItem->item->id]) : '' ?></td>
                    <td><?= $this->Form->postLink($this->Number->format($customersItem->target_quantity),'set-trigger-levels',[]) ?></td>
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
