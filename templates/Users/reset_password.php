<?php

use App\Form\ResetPasswordForm;
use App\Model\Entity\User;
use App\View\AppView;
use Cake\Routing\Router;

/**
 * @var AppView $this
 * @var User $User
 * @var ResetPasswordForm $context
 */

echo $this->Html->tag('h3', 'Please enter a new password.');


echo $this->Form->create($context, ['action' => Router::url(
    [
        'controller' => 'Users',
        'action' => 'resetPassword',
        $User->username,
        $User->idHash()
    ])
]);
//$this->Form->create();
echo $this->Form->control('new_password', ['type' => 'password']);
echo $this->Form->control('confirm_password', ['type' => 'password']);
echo $this->Form->submit();
echo $this->Form->end();
