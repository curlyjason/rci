<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\OrderLine> $orderLines
 */
?>
<div class="orderLines index content">
    <?= $this->Html->link(__('New Order Line'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Order Lines') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('sku') ?></th>
                    <th><?= $this->Paginator->sort('vendor_sku') ?></th>
                    <th><?= $this->Paginator->sort('uom') ?></th>
                    <th><?= $this->Paginator->sort('quantity') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderLines as $orderLine): ?>
                <tr>
                    <td><?= $this->Number->format($orderLine->id) ?></td>
                    <td><?= h($orderLine->name) ?></td>
                    <td><?= h($orderLine->sku) ?></td>
                    <td><?= h($orderLine->vendor_sku) ?></td>
                    <td><?= h($orderLine->uom) ?></td>
                    <td><?= $orderLine->quantity === null ? '' : $this->Number->format($orderLine->quantity) ?></td>
                    <td><?= h($orderLine->created) ?></td>
                    <td><?= h($orderLine->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $orderLine->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $orderLine->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $orderLine->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orderLine->id)]) ?>
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
