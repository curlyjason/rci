<?php
/**
 * @var \App\View\AppView $this
 * @var \Laminas\Diactoros\UploadedFile $file
 */

?>
<?= $this->Form->create(null, ['type' => 'file', 'action' => 'bulkImport']) ?>
<?= $this->Form->control('upload', ['type' => 'file']) ?>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>
