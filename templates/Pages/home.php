<?php
/* @var \App\View\AppView $this */

use Cake\Core\Configure;

?>
<p><a href="<?= $this->Url->build('/take-inventory') ?>">Take Inventory</a></p>
<p><a href="<?= $this->Url->build('/set-trigger-levels') ?>">Set PAR</a></p>
<p><a href="<?= $this->Url->build('/order-now') ?>">Order Now</a></p>

<?= $this->element('layout/admin_menus', ['anchorDecorator' => 'p']) ?>

<p><a href="users/logout">Logout</a></p>

