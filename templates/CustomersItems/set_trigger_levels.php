<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 * @var object $masterFilterMap for js live filtering
 * @var \App\Model\Entity\User $user
 */

use App\Model\Entity\CustomersItem;

//<editor-fold desc="SPECIALIZED PAGE STYLES : viewblock = style">
$this->append('style');
?>
<style>
    tbody input.target_quantity {
        font-size: 200%;
        max-width: 7rem;
    }
    tr.hide {
        display: none;
    }
</style>
<?php
$this->end();
//</editor-fold>

//<editor-fold desc="LOCAL UTILITY FUNCTIONS">
$getId = function($data) {
    return $data->id;
};

$description = function(CustomersItem $input):string {
    $itemName = $input?->item->name ?? 'Unknown';
    $itemQuantity = $input->quantity ?? '?';
    $pattern = '<span class="name">%s</span><br /><span style="font-size: small;">[Current inventory level: %s]</span>';

    return sprintf($pattern, $itemName, $itemQuantity);
};
$postOnShelf = function(CustomersItem $input) use ($getId):string {
    $this->start('onShelfForm');
    echo $this->Form->create($input, ['id' => $getId($input)]);
    echo $this->Form->control('target_quantity', [
        'class' => 'target_quantity tipMe',
        'label' => false,
        'value' => $input->target_quantity,
        'title' => 'Amount on shelf',
        'id' => "target_quantity-{$getId($input)}",
        'type' => 'char',
    ]);
    echo $this->Form->end();
    $this->end();

    return $this->fetch('onShelfForm');
};
$outputTableRow = function($customersItem) use ($description, $postOnShelf, $getId):string {
    $this->start('tableRows');
        echo "<tr id=\"{$getId($customersItem)}\">";
        echo "<td>{$description($customersItem)}</td>";
        echo "<td>{$postOnShelf($customersItem)}</td>";
        echo '</tr>';
    $this->end();

    return $this->fetch('tableRows');
};
//</editor-fold>

$this->append('script', $this->Html->script('trigger_tools.js'));

?>
<div class="customersItems index content">
    <h3><?= __('Set Reorder Trigger Levels') ?></h3>
    <?php
    echo $this->Form->create();
    echo $this->Form->control('filter');
    echo $this->Form->end();
    ?>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('item_id') ?></th>
                <th><?= $this->Paginator->sort('quantity', 'Target Level') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($customersItems as $customersItem):
                echo $outputTableRow($customersItem);
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
<script>
    const itemMap = <?= json_encode($masterFilterMap) ?> ;
</script>
