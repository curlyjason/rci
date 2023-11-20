<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$cakeDescription = env('SHORT_NAME') . '/' . env('WEB_PORT') . '/' . env('DB_PORT');
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-titlex">
            <a href="<?= $this->Url->build('/take-inventory') ?>">Take Inventory</a> |
            <a href="<?= $this->Url->build('/set-trigger-levels') ?>">Set Trigger Levels</a> |
            <a href="<?= $this->Url->build('/order-now') ?>">Order Now</a>
            <?php if (Configure::read('debug')) : ?>
                | <a href="<?= $this->Url->build('api/set-inventory.json') ?>">Set Inventory</a> |
            <a href="<?= $this->Url->build('api/set-trigger.json') ?>">Set Trigger</a> |
            <a href="<?= $this->Url->build('api/order-item.json') ?>">Order</a> |
            <a href="<?= $this->Url->build('/') ?>"><span>Cake</span>PHP</a>
            <?php endif; ?>
        </div>
        <div class="top-nav-links">
            <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/">Documentation</a>
            <a target="_blank" rel="noopener" href="https://api.cakephp.org/">API</a>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
