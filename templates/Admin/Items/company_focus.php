<?php

use App\Model\Entity\Customer;
use \App\View\AppView;

/**
 * @var AppView $this
 * @var Customer[] $customers
 */

$action = $this->request->getPath();

$userChoice = function($user) use ($action) {
    return $user->isAdmin()
        ? ''
        :   '<dd>'
            . $this->Form->postLink($user->email, null, ['data' => ['id' => $user->id]])
            . '</dd>';
}

?>
<?php foreach ($customers as $customer) : ?>
    <dt><?= $customer->name ?></dt>
    <?php foreach ($customer->users as $user) :?>
        <?= $userChoice($user) ?>
    <?php endforeach; ?>

<?php endforeach; ?>
