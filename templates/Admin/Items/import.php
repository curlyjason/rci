<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Utilities\ImportItems $importer
 */

$archive = $importer->openArchiveToRead();
$errors = $importer->openErrorsToRead();

?>
<style>
    th, td {
        border: #cfdbe5 thin solid;
    }
    tr.err td {
        column-span: 3;
    }
</style>
<?= $this->Html->link('Download Error File', 'admin/items/download-error-file', ['class' => 'button']) ?>
<table>
    <thead>
        <?= $this->Html->tableHeaders(fgetcsv($errors)) ?>
    </thead>
    <tbody>
    <?php while ($line = fgetcsv($errors)) : ?>
        <?= $this->Html->tableCells($line) ?>
    <?php endwhile; ?>
    </tbody>
</table>

<table>
    <thead>
        <?= $this->Html->tableHeaders(fgetcsv($archive)) ?>
    </thead>
    <tbody>
    <?php while ($line = fgetcsv($archive)) : ?>
        <?= $this->Html->tableCells($line) ?>
    <?php endwhile; ?>
    </tbody>
</table>
