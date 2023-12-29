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
 * @var AppView $this
 */

use App\View\AppView;
use Cake\Core\Configure;

$jQuery_path = Configure::read('debug')
    ? 'node_modules/jquery/dist/jquery.js'
    : 'node_modules/jquery/dist/jquery.slim.js';
$this->prepend('script', $this->Html->script($jQuery_path));
$this->append('script', $this->Html->script('tooltip.js'));

$cakeDescription = env('SHORT_NAME') . '/' . env('WEB_PORT') . '/' . env('DB_PORT');

?>
<!DOCTYPE html>
<html lang="en">
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
    <!-- Site Wide CSS overrides-->
    <style>
        .top-nav #myLinks {
            display: none;
        }
        #myLinks a, a#masterMenu {
            display: block;
        }
        span.rci-tool-tip {
            position: absolute;
            display: inline-block;
            border-radius: 6px;
            font-size: x-small;
            padding: 2px 1rem;
            background-color: gold;
            margin-bottom: -10px;
            white-space: nowrap;
            overflow: visible;
            width: auto;
        }
        span.rci-arrow {
            position: absolute;
            display: inline-block;
            width: 0;
            border-top: 10px;
            border-style: solid;
            border-color: gold transparent transparent transparent;
        }
        .section-break {
            border-top: thin solid black;
        }
        .top-nav a {
            font-size: smaller;
        }
    </style>
    <!-- Menu toggle listener-->
    <script>
        function menuToggle() {
            let x = document.getElementById("myLinks");
            let y = document.getElementById("masterMenu");
            if (x.style.display === "block") {
                x.style.display = "none";
                y.style.display = "block"
            } else {
                x.style.display = "block";
                y.style.display = "none";
            }
        }
    </script>
</head>
<body>
<nav class="top-nav">
        <!-- Expanded menu revealed onClick -->
        <div id="myLinks" class="top-nav-titlex">
            <div class="top-nav-links side-nav">
                <a href="javascript:void(0);" onclick="menuToggle()">Close Menu</a>
            </div>
            <?= $this->element('layout/public_menus') ?>
            <?= $this->element('layout/admin_menus') ?>
            <a class="section-break" href="/users/logout">Logout</a>
        </div>
        <!-- Minimal menu displayed by default -->
        <div class="top-nav-links">
            <div id="masterMenu">
                <a href="javascript:void(0);" onclick="menuToggle()">Menu</a>
                <br/><span style="font-size: x-small">Welcome <?= $this->getIdentity()?->email ?></span>
            </div>
    <?php if (!is_null($this->getIdentity())) : ?>
            <?php endif; ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <h4>Rods & Cones Inventory</h4>
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
