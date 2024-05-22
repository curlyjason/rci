<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CustomersItem> $customersItems
 * @var \App\Model\Entity\User $user
 * @var string $nextInventoryDate
 */

use App\Model\Entity\CustomersItem;

//<editor-fold desc="SPECIALIZED PAGE STYLES : viewblock = style">
$this->append('style');
?>
<style>
    tbody.todo input.quantity, tbody.complete input.quantity{
        font-size:200%;
        max-width:7rem;
        margin-bottom:5px;
        color:darkred;
    }
    tbody.complete input.quantity{
        color: green;
    }
    tbody.todo button.ok-button, tbody.complete button.ok-button{
        border-color:darkgreen;
        background-color:white;
        color:green;
        height:2.8rem;
        line-height:2.8rem;
        padding:0 2rem;;
    }
    tbody.complete button.ok-button{
        display: none;
        background-color:green;
        color:white;
    }
    h3.defined {
        margin-bottom: 0;
    }
    p.definition{
        font-size: small;
        margin-bottom: 0;
    }
    p.definition:last-of-type{
        margin-bottom: 2rem;
    }
    .hide {
        display: none;
    }
</style>
<?php
$this->end();
//</editor-fold>

//<editor-fold desc="LOCAL HELPER FUNCTIONS">
$description = function(CustomersItem $input):string {
    $itemName = $input?->item->name ?? 'Unknown';
    $itemTrigger = $input->target_quantity ?? '?';
    $pattern = '<span class="name">%s</span><br /><span style="font-size: small;">[PAR: %s]</span>';

    return sprintf($pattern, $itemName, $itemTrigger);
};
$postOnShelf = function(CustomersItem $input):string {
    $this->start('onShelfForm');
    echo $this->Form->create($input, ['id' => $input->id]);
    echo $this->Form->control('quantity', [
        'class' => 'quantity tipMe',
        'label' => false,
        'value' => $input->quantity,
        'title' => 'Amount on shelf',
        'id' => "quantity-$input->id",
        'type' => 'char',
    ]);
//    echo $this->Form->checkbox($input->quantity . 'is correct');
//    echo $this->Form->control('ok', ['type' => 'checkbox', 'label'=>$input->quantity . ' is correct','value' => 1]);
//    echo $this->Form->control('ok', ['type' => 'checkbox', 'label'=> "$input->quantity OK",'value' => 1, 'style' => 'width: 24px; height: 24px;']);
        echo $this->Form->button("$input->quantity ✓", [
        'class' => 'button ok-button',
        'type' => 'button',
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
//</editor-fold>

$this->append('script', $this->Html->script('inventory_tools.js'));
?>
<div class="customersItems index content">
    <h3 class="defined"><?= __('Take Inventory') ?></h3>

    <p class="definition hide" id="open" style="color: firebrick; font-weight: bold">Inventory now open.</p>
    <p class="definition hide" id="nextDate">Next inventory due on <?= $nextInventoryDate?></p>
    <p class="definition">The To Do section shows the last inventory count and your current PAR.</p>
    <p class="definition">The Complete section shows your current inventory count (as you enter it).</p>

    <h4 id="todoHeader" class="hide">To Do</h4>
    <h2 id="doneHeader" class="hide" style="color: white; background-color: darkgreen">Inventory complete, thank you.</h2>
    <div class="table-responsive todo">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('quantity', 'Last Count') ?></th>
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
    <h3>Submitted Inventory</h3>
    <div class="table-responsive complete">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('quantity', 'Current Count') ?></th>
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

