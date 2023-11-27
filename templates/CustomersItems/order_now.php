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
    'tbody input.target_quantity' => [
        'font-size'=>'200%',
        'max-width'=>'7rem',
    ],
    'tr.hide' => [
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
    $selectLink = $this->Html->link('Add to order','#');
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
    echo $this->Form->control('quantity', [
        'class' => 'quantity',
        'label' => false,
        'value' => $input->quantity,
        'title' => 'Amount on shelf',
        'id' => "quantity-$input->id",
        'type' => 'char',
    ]);
    $this->end();

    return $this->fetch('onShelfForm');
};
$formTableRow = function($customersItem) use ($description, $postOnShelf) :string {
    $this->start('tableRows');
        echo "<tr id=\"$customersItem->id\" class=\"hide\">";
        echo "<td>{$description($customersItem)}</td>";
        echo "<td>{$postOnShelf($customersItem)}</td>";
        echo '</tr>';
    $this->end();

    return $this->fetch('tableRows');
};
//</editor-fold>

//osd($masterFilterMap);
//osd($items);

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
                <th>Quantity</th>
                <th><?= $this->Paginator->sort('item_id') ?></th>
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
