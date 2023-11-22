<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 */

use App\Model\Entity\CustomersItem;

$description = function(CustomersItem $input):string {
    $itemName = $input?->item->name ?? 'Unknown';
    $itemTrigger = $input->target_quantity ?? '?';
    $pattern = '<span class="name">%s</span><br /><span style="font-size: small;">[Reorder trigger level: %s]';

    return sprintf($pattern, $itemName, $itemTrigger);
};
$postOnShelf = function(CustomersItem $input):string {
    $this->start('onShelfForm');
    echo $this->Form->create($input, ['id' => $input->id]);
    echo $this->Form->control('quantity', [
        'label' => false,
        'value' => $input->quantity,
        'style' => 'font-size: 200%; max-width: 7rem;',
        'title' => 'Amount on shelf',
        'id' => "quantity-$input->id",
        'type' => 'char',
    ]);
    echo $this->Form->end();
    $this->end();

    return $this->fetch('onShelfForm');
};
$outputTableRow = function(bool $shouldOutput, $customersItem) use ($description, $postOnShelf) :string {
    $this->start('tableRows');
        if ($shouldOutput):
            echo "<tr id=\"$customersItem->id\">";
            echo "<td>{$description($customersItem)}</td>";
            echo "<td>{$postOnShelf($customersItem)}</td>";
            echo '</tr>';
        endif;
    $this->end();

    return $this->fetch('tableRows');
};

$this->append('script', $this->Html->script('inventory_tools.js'));

?>
<div class="customersItems index content">
    <?= $this->element('new_item_button') ?>
    <h3><?= __('Customers Items (Filter top customer and name them here)') ?></h3>
    <h3>To Do</h3>
    <div class="table-responsive todo">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('quantity', 'On Shelf') ?></th>
                </tr>
            </thead>
            <tbody class="todo">
            <?php
            foreach ($customersItems as $customersItem):
                echo $outputTableRow(!$customersItem->hasBeenInventoried(), $customersItem);
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
    <h3>Complete</h3>
    <div class="table-responsive complete">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('quantity', 'On Shelf') ?></th>
                </tr>
            </thead>
            <tbody class="complete">
            <?php
            foreach ($customersItems as $customersItem):
                echo $outputTableRow($customersItem->hasBeenInventoried(), $customersItem);
            endforeach;
            ?>
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
