<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ItemsVendor $itemsVendor
 * @var \Cake\Collection\CollectionInterface|string[] $items
 * @var \Cake\Collection\CollectionInterface|string[] $vendors
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Items Vendors'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="itemsVendors form content">
            <?= $this->Form->create($itemsVendor) ?>
            <fieldset>
                <legend><?= __('Add Items Vendor') ?></legend>
                <?php
                    echo $this->Form->control('sku');
                    echo $this->Form->control('item_id', ['options' => $items]);
                    echo $this->Form->control('vendor_id', ['options' => $vendors]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
