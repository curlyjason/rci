<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomersItem $customersItem
 * @var string[]|\Cake\Collection\CollectionInterface $customers
 * @var string[]|\Cake\Collection\CollectionInterface $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $customersItem->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $customersItem->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Customers Items'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="customersItems form content">
            <?= $this->Form->create($customersItem) ?>
            <fieldset>
                <legend><?= __('Edit Customers Item') ?></legend>
                <?php
                    echo $this->Form->control('quantity');
                    echo $this->Form->control('target_quantity');
                    echo $this->Form->control('customer_id', ['options' => $customers]);
                    echo $this->Form->control('item_id', ['options' => $items]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
