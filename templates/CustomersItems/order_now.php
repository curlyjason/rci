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
    '.submit input' => [
        'font-size' => '170%',
        'height' => '6.5rem',
        'position' => 'fixed',
        'bottom' => '10%',
        'border' => 'thin solid black',
        'background-color' => 'darkgreen',
    ],
    'tbody input.order_quantity' => [
        'font-size'=>'200%',
        'max-width'=>'7rem',
        'margin-top' => '1rem',
        'margin-bottom' => '.3rem',
    ],
    'tr.order td' => [
        'padding-left' => '1rem',
    ],
    'tr.order' => [
        'background-color' => 'beige',
    ],
    '.hide' => [
        'display'=>'none',
    ],
    'td' => [
        'padding-top' => '3px',
        'padding-bottom' => '0px',
    ],
    '.lineAdd' => [
        'background-color' => 'lightgrey',
        'border-color' => 'green'
    ],
    'table' => [
        'margin-bottom' => '15rem',
    ],
];

$this->append('style');
echo "\n<style>\n";
foreach ($style_overrides as $potentialItemCell => $override) {
    echo $potentialItemCell . ' { ' . $this->Html->style($override) . " }\n";
}
echo "</style>\n";
$this->end();
//</editor-fold>

//<editor-fold desc="LOCAL UTILITY FUNCTIONS">
$getId = function($data) {
    return $data->id;
};
$orderQtyInput = function(CustomersItem $input):string {
    $this->start('orderQtyInput');
    echo $this->Form->control('order_quantity', [
        'class' => 'order_quantity hide',
        'label' => false,
        'name' => 'order_quantity[]',
        'value' => '',
        'title' => 'Amount on shelf',
        'id' => "order_quantity-$input->id",
        'type' => 'char',
    ]);
    echo $this->Form->control('id', ['name' => 'id[]', 'type' => 'hidden', 'value' => $input->id]);
    $this->end();

    return $this->fetch('orderQtyInput');
};

$potentialItemCell = function(CustomersItem $input) use ($getId, $orderQtyInput):string {
    $addButton = $this->Form->button('Add to order',[
        'class' => 'toggleOnOrder lineAdd',
        'type' => 'button',
    ]);
    $removeButton = $this->Form->button('Remove from order',[
        'class' => 'toggleOnOrder lineRemove hide',
        'type' => 'button',
    ]);
    $itemName = $input?->item->name ?? 'Unknown';
    $cellId = "td-{$getId($input)}";

    return "
<td id=\"$cellId\" colspan='2'>
        {$orderQtyInput($input)}
    <div>
        <span class=\"name\">$itemName</span><br/>
        $addButton $removeButton
    </div>
</td>
";
};
$outputSelectorRow = function($customersItem) use ($potentialItemCell, $getId):string {
    $this->start('tableRows');
    echo "<tr id=\"{$getId($customersItem)}\">";
    echo $potentialItemCell($customersItem);
    echo '</tr>';
    $this->end();

    return $this->fetch('tableRows');
};
//</editor-fold>

$this->append('script', $this->Html->script('order_tools.js'));

?>
<div class="customersItems index content">
    <h3><?= __('Make an Order') ?></h3>
    <?php
    echo $this->Form->create();
    echo $this->Form->control('filter');
    echo $this->Form->control('review', [
        'type' => 'radio',
        'options' => ['On Order', 'All Items']
    ]);
    echo $this->Form->end();
    ?>
    <div class="table-responsive">
        <?= $this->Form->create(); ?>
        <?= $this->Form->submit('Place Order') ?>
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
        <?= $this->Form->end(); ?>
    </div>
</div>
<script>
    const itemMap = <?= json_encode($masterFilterMap) ?> ;
</script>
