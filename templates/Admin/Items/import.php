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

<?php if ($importer->errorCount) : ?>
<?= $this->Html->link('Download Error File', 'admin/items/download-file/' . htmlentities($importer::ERROR_PATH), ['class' => 'button']) ?>

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

<?php
endif;
if ($importer->archiveCount) :
?>
    <?= $this->Html->link('Download Archive File', 'admin/items/download-file/' . htmlentities($importer->archivePath), ['class' => 'button']) ?>

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

<?php endif; ?>
