<?php

namespace App\Forms;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\Locator\LocatorAwareTrait;

class OrderNowForm extends Form
{
    use LocatorAwareTrait;

    public array $posted = [
        'order_now' => '1',
        'order_quantity' => [
            0 => '0',
            1 => '',
            2 => '7',
            3 => '',
        ],
        'id' => [
            0 => '291608622',
            1 => '291608625',
            2 => '291608628',
            3 => '1638876896',
        ],
    ];

    public function execute(array $data, array $options = []): bool
    {
        $entity = false;
        if (parent::execute($data, $options)) {
            $ITable = $this->fetchTable('Items');
            $OLTable = $this->fetchTable('OrderLines');
            $order = $OLTable->Orders->newEntity([
                'order_number' => uniqid(),
                'ordered_by' => $this->getData('name'),
                'ordered_by_email' => $this->getData('email'),
                'status' => 'new',
                'order_lines' => [],
            ]);
            foreach ($this->getData('order_quantity') as $index => $qty) {
                if ($qty) {
                    $itemId = $this->getData('id')[$index];
                    $entity = $OLTable->newEntity($ITable->get($itemId)->toArray());
                    $entity->set('order_quantity', $qty);
                    $order['order_lines'][] = $entity;
                }
            }
            $this->set('result', $entity);
            return true;
        }
        return false;
    }
}
