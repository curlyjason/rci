<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomersItem $customersItem
 * @var \Cake\Collection\CollectionInterface|string[] $customers
 * @var \Cake\Collection\CollectionInterface|string[] $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Customers Items'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="customersItems form content">
            <?= $this->Form->create($customersItem) ?>
            <fieldset>
                <legend><?= __('Add Customers Item') ?></legend>
                <?php
                    echo $this->Form->control('quantity');
                    echo $this->Form->control('target_quantity');
                    echo $this->Form->control('next_inventory', ['empty' => true]);
                    echo $this->Form->control('customer_id', ['options' => $customers]);
                    echo $this->Form->control('item_id', ['options' => $items]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
