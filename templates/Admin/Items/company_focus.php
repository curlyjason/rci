<?php

use App\Model\Entity\Customer;
use \App\View\AppView;

/**
 * @var AppView $this
 * @var Customer[] $customers
 */

$action = $this->request->getPath();

$customerChoice = function($customer) use ($action) {
    return $this->Form->postLink($customer->name, null, ['data' => ['customer_id' => $customer->id]]);
}

?>
<?php foreach ($customers as $customer) : ?>
    <p><?= $customerChoice($customer) ?></p>
<?php endforeach; ?>
