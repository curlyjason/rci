<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Database\Schema\TableSchemaInterface $schema
 */
?>

<style>
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
        margin-left: 23%;
    }
    div.input.select label {
        width: 30%;
    }
    span.dest {
        display: inline-block;
        width: 80%;
    }
    div.input.select select {
        margin-bottom: 0;
    }
</style>

<?php
$inputCols = [
    'Product/Service',
    'Type',
    'Description',
    'Price',
    'Cost',
    'Qty On Hand',
];

?>

<?= $this->Form->create() ?>
<?php foreach ($inputCols as $inputCol) : ?>
    <?= $this->Form->control(
        $inputCol . '.dest',
        [
            'empty' => 'Choose destination column',
            'options' => array_combine($schema->columns(),$schema->columns()),
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
            'options' => array_combine(['allow', 'discard', 'update'],['allow', 'discard', 'update']),
            'type' => 'radio',
            'label' => 'On Duplicate:',
            'value' => '0',
        ]
    ) ?>
<?php endforeach; ?>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>

<?php
osd ($post);
