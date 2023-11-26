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

$jQuery_path = Configure::read('debug')
    ? 'node_modules/jquery/dist/jquery.js'
    : 'node_modules/jquery/dist/jquery.slim.js';
//$jQuery_path = Configure::read('debug')
//    ? 'https://code.jquery.com/jquery-3.7.1.js'
//    : 'https://code.jquery.com/jquery-3.7.1.min.js';

$this->prepend('script', $this->Html->script($jQuery_path));

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
    <?= $this->fetch('style') ?>
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
            <?php endif; ?>
        </div>
        <div class="top-nav-links">
            <a href="#">Welcome <?= $this->request->getSession()->read('Auth')?->email ?> </a>
            <?php if (!is_null($this->request->getSession()->read('Auth'))) : ?>
            <a href="users/logout">Logout</a>
            <?php endif; ?>
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
