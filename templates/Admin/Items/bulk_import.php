<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Database\Schema\TableSchemaInterface $schema
 * @var array $inputCols
 * @var resource $import
 */
?>

<style>
    div.inputCol {
        width: 50%;
        background-color: rgba(229, 227, 207, 0.25);
        padding: 1rem 2rem;
    }
    div.inputCol:nth-child(2n) {
        background-color: rgba(207, 219, 229, 0.15);
    }
    div.input.select, div.input.radio {
        display: flex;
    }
    div.input.radio label {
        font-weight: normal;
        font-size: smaller;
    }
    div.input.radio input {
        margin-left: 1.2rem;
    }
    div.input.radio > label:first-child {
        margin-left: 36.5%;
    }
    div.input.select label {
        width: 60%;
        padding-top: 4px;  }
    span.dest {
        display: inline-block;
        width: 70%;
    }
    div.input.select select, div.input.radio {
        margin-bottom: 0;
    }
</style>

<?php
$line = fgetcsv($import);
while ($line) {
    echo $this->Html->para('', implode('::', $line));
    $line = fgetcsv($import);
//    fwrite($import, implode(',, ',($line)));
}


?>

<?= $this->Form->create() ?>
<?php foreach ($inputCols as $inputCol) : ?>
<div class="inputCol">
    <?= $this->Form->control(
        $inputCol . '.dest',
        [
            'empty' => 'Choose destination column',
            'options' => array_combine($schema->columns(),$schema->columns()),
//            'options' => array_combine($schema,$schema),
            'id' => $inputCol,
            'class' => 'map_select',
            'label' => $this->Html->tag(
                'span',
                $inputCol,
                ['class' => 'dest']
                )
                . $this->Html->tag(
                    'span',
                    ' <--> ',
                    ['class' => 'divider']
                ),
            'escape' => false,
        ]) ?>
    <?= $this->Form->control(
        $inputCol . '.dups',
        [
            'options' => array_combine(['allow', 'discard'],['allow', 'discard']),
            'type' => 'radio',
            'label' => 'On Duplicate:',
            'value' => $this->request->getData($inputCol.'.dups') ?? 'allow',
        ]
    ) ?>
</div>
<?php endforeach; ?>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>

<?php
osd ($post);
