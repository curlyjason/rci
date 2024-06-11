<?php

use App\View\AppView;

/**
 * @var AppView $this
 */
$User = new \App\Model\Entity\User();

echo $this->Form->create($User);
//$this->Form->unlockField('email');
echo $this->Form->control('email', ['label' => 'Please enter your email']);
echo $this->Form->submit();
echo $this->Form->end();

