<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrderLine $orderLine
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $orderLine->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $orderLine->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Order Lines'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="orderLines form content">
            <?= $this->Form->create($orderLine) ?>
            <fieldset>
                <legend><?= __('Edit Order Line') ?></legend>
                <?php
                    echo $this->Form->control('qb_code');
                    echo $this->Form->control('name');
                    echo $this->Form->control('quantity');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
