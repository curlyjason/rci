<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customer $customer
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Customer'), ['action' => 'edit', $customer->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Customer'), ['action' => 'delete', $customer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customer->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Customers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Customer'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="customers view content">
            <h2><?= h($customer->name) ?></h2>
            <div class="related">
                <h4><?= __('Items') ?></h4>
                <?php if (!empty($customer->customers_items)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Customer Items Id') ?></th>
                            <th><?= __('Name') ?></th>

                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($customer->customers_items as $customerItem) : ?>
                        <?php $item = $customerItem->item ?>
                        <tr>
                            <td><?= h($customerItem->id) ?></td>
                            <td><?= h($item->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Items', 'action' => 'view', $item->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Items', 'action' => 'edit', $item->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'CustomersItems', 'action' => 'customerDelete', $customerItem->id, $customer->id], ['confirm' => __('Are you sure you want to remove the connection to # {0}?', $customerItem->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
