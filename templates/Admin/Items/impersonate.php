<?php

use App\Model\Entity\Customer;
use \App\View\AppView;

/**
 * @var AppView $this
 * @var Customer[] $customers
 */

$userChoice = function($user) {
    return $user->isAdmin()
        ? ''
        : '<dd>'
            . $this->Form->postLink($user->email)
            . '</dd>';
}

?>
<?php foreach ($customers as $customer) : ?>
    <dt><?= $customer->name ?></dt>
    <?php foreach ($customer->users as $user) :?>
        <?= $userChoice($user) ?>
    <?php endforeach; ?>

<?php endforeach; ?>
