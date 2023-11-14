<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ItemsVendor $itemsVendor
 * @var string[]|\Cake\Collection\CollectionInterface $items
 * @var string[]|\Cake\Collection\CollectionInterface $vendors
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $itemsVendor->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $itemsVendor->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Items Vendors'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="itemsVendors form content">
            <?= $this->Form->create($itemsVendor) ?>
            <fieldset>
                <legend><?= __('Edit Items Vendor') ?></legend>
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
