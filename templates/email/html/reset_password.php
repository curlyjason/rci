<?php

use App\Model\Entity\User;
use App\View\AppView;

/**
 * @var AppView $this
 * @var User $User
 */

$link = $this->Html->link('Reset Password',
    [
        'controller' => 'users',
        'action' => 'reset_password',
        $User->email, $User->getDigest()
    ],
    ['fullBase' => true]);
?>
<h3>Password reset for Rods and Cones</h3>
<h4>for <?= $User->email ?></h4>
<p>Please follow this link</p>
<?= $link ?>
<p>to reset your password.</p>

<p><strong>This link expires in 24 hours.</strong></p>
