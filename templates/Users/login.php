<?php
/**
 * @var \App\View\AppView $this
 */
?>
<h1>Log In</h1>
<div class="users form content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
        <?= $this->Html->link('forgot password', ['controller' => 'users', 'action' => 'forgot_password']); ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
