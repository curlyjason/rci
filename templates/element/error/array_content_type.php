<?php

use App\View\AppView;
use Cake\Error\Debugger;
/**
 * In a table, show the index number and type of value for array elements
 *
 * @var App\View\AppView $this
 * @var array $error_set
 */
?>
<table style="width: 50%">
    <caption style="text-align: left"><?= $caption ?></caption>
    <tbody>
    <?= $this->Html->tableHeaders(['Index', 'Value']) ?>
    <?php
    foreach ($error_set as $index => $value) {
        $val = Debugger::getType($value);

        echo "<tr><td>$index</td><td>$val</td></tr>\n";
    }
    ?>
    </tbody>
</table>
