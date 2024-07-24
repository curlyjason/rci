<?php

use App\Model\Entity\User;
use App\View\AppView;

/**
 * @var AppView $this
 * @var User $User
 */

$link = $this->Html->link('Set Password',
    [
        'controller' => 'users',
        'action' => 'reset_password',
        $User->email, $User->getDigest()
    ],
    ['fullBase' => true]);
?>
<h3>Welcome to Rods and Cones Consumable </h3>
<h4>Your username is <?= $User->email ?></h4>
<p>Please follow this link</p>
<?= $link ?>
<p>to set your password.</p>

<p><strong>This link expires in 24 hours.</strong></p>
