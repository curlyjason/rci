<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 * @var object $masterFilterMap
 * @var array $items
 * @var \App\Model\Entity\User $user
 */

use App\Model\Entity\CustomersItem;

//<editor-fold desc="SPECIALIZED PAGE STYLES : viewblock = style">
$style_overrides = [
    'tbody input.order_quantity' => [
        'font-size'=>'200%',
        'max-width'=>'7rem',
        'margin-top' => '1rem',
    ],
    '.hide' => [
        'display'=>'none',
    ],
    'td' => [
        'padding-top' => '3px',
        'padding-bottom' => '0px',
    ]
];

$this->append('style');
echo "\n<style>\n";
foreach ($style_overrides as $selector => $override) {
    echo $selector . ' { ' . $this->Html->style($override) . " }\n";
}
echo "</style>\n";
$this->end();
//</editor-fold>

//<editor-fold desc="LOCAL UTILITY FUNCTIONS">
$getId = function($data) {
    return $data->id;
};

$selector = function(CustomersItem $input):string {
    $selectLink = $this->Form->button('Add to order',[
        'data-target' => "#ol-$input->id",
        'class' => 'toggleOnOrder button-clear',
        'type' => 'button',
    ]);
;
    $itemName = $input?->item->name ?? 'Unknown';
    $pattern = '<td>%s</td><td><span class="name">%s</span></td>';

    return sprintf($pattern, $selectLink, $itemName);
};
$outputSelectorRow = function($customersItem) use ($selector, $getId):string {
    $this->start('tableRows');
    echo "<tr id=\"{$getId($customersItem)}\">";
    echo $selector($customersItem);
    echo '</tr>';
    $this->end();

    return $this->fetch('tableRows');
};

$description = function(CustomersItem $input):string {
    $itemName = $input?->item->name ?? 'Unknown';
    $itemTrigger = $input->target_quantity ?? '?';
    $currInventory = $input->quantity;
    $pattern = '<span class="name">%s</span><br /><span style="font-size: small;">[Reorder trigger level: %s | Current inventory: %s]';

    return sprintf($pattern, $itemName, $itemTrigger, $currInventory);
};
$postOnShelf = function(CustomersItem $input):string {
    $this->start('onShelfForm');
    echo $this->Form->control('order_quantity', [
        'class' => 'order_quantity',
        'label' => false,
        'name' => 'order_quantity[]',
        'value' => '',
        'title' => 'Amount on shelf',
        'id' => "order_quantity-$input->id",
        'type' => 'char',
    ]);
    echo $this->Form->control('id', ['name' => 'id[]', 'type' => 'hidden', 'value' => $input->id]);
    $this->end();

    return $this->fetch('onShelfForm');
};
$removeButton = function(CustomersItem $input):string {
    return $this->Form->button('Remove from order',[
        'data-target' => "#ol-$input->id",
        'class' => 'toggleOnOrder button-clear',
        'type' => 'button',
    ]);
};
$formTableRow = function($customersItem) use ($description, $postOnShelf, $removeButton) :string {
    $this->start('tableRows');
        echo "<tr id=\"ol-$customersItem->id\" class=\"\">";
        echo "<td>{$removeButton($customersItem)}</td>";
        echo "<td>{$description($customersItem)}</td>";
        echo "<td>{$postOnShelf($customersItem)}</td>";
        echo '</tr>';
    $this->end();

    return $this->fetch('tableRows');
};
//</editor-fold>

$this->append('script', $this->Html->script('order_tools.js'));

?>
<div class="customersItems index content">
    <?= $this->element('new_item_button') ?>
    <h2><?= __('Make an Order') ?></h2>
    <h3><?= __($user->customer->name) ?></h3>
    <?php
    echo $this->Form->create();
    echo $this->Form->control('filter');
    echo $this->Form->end();
    ?>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th></th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($customersItems as $customersItem):
                echo $outputSelectorRow($customersItem);
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
    <div class="table-responsive order-lines">
        <h4>Order Items</h4>
        <?= $this->Form->create(); ?>
        <?= $this->Form->submit('Place Order') ?>
        <table>
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($customersItems as $customersItem):
                echo $formTableRow($customersItem);
            endforeach;
            ?>
            </tbody>
        </table>
        <?= $this->Form->end(); ?>
    </div>
</div>
<script>
    const itemMap = <?= json_encode($masterFilterMap) ?> ;
</script>
