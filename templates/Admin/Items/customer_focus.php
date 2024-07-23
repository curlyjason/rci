<?php

use App\Model\Entity\Customer;
use \App\View\AppView;

/**
 * @var AppView $this
 * @var Customer[] $customers
 */

$customerChoice = function($customer){
    return $this->Form->postLink(
        $customer->name,
        null,
        ['data' => [
            'customer_id' => $customer->id,
            'customer_focus' => true, //signal for CustomerFocus to process the post
            'focusing' => true, //signal to items/add to ignore the post
        ]]);
}

?>
<?php foreach ($customers as $customer) : ?>
    <p><?= $customerChoice($customer) ?></p>
<?php endforeach; ?>
