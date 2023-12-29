<?php
/**
 * @var \App\View\AppView $this
 * @var ?\App\Model\Entity\User $identity
 * @var ?string $anchorDecorator
 */

$isDecorated = ($anchorDecorator ?? '') !== '';

$decorateOpen = $isDecorated ? "<$anchorDecorator>" : '';
$decorateClose = $isDecorated ? "</$anchorDecorator>" : '';
$sectionBreak = $isDecorated ? '' : 'class="section-break"';

if ($this->getIdentity()?->isAdmin()):
?>
    <?= $decorateOpen ?> <a <?= $sectionBreak ?>  href="<?= $this->Url->build('admin/users') ?>">Users</a><?= $decorateClose ?>
    <?= $decorateOpen ?> <a href="<?= $this->Url->build('admin/items') ?>">Items</a><?= $decorateClose ?>
    <?= $decorateOpen ?> <a href="<?= $this->Url->build('admin/orders') ?>">Orders</a><?= $decorateClose ?>
    <?= $decorateOpen ?> <a href="<?= $this->Url->build('admin/customers') ?>">Customers</a><?= $decorateClose ?>
<?php endif ?>
