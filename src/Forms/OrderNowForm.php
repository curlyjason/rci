<?php

namespace App\Forms;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validator;

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

    /**
     * @param array $data
     * @param array $options
     * @return bool
     * @throws RecordNotFoundException
     */
    public function execute(array $data, array $options = []): bool
    {
        if (parent::execute($data)) {
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
            $this->set('result', $order);

            return true;
        }
        return false;
    }

    public function setSchema(Schema $schema)
    {
        $schema->addField('order_now', ['type' => 'bool']);

        return parent::setSchema($schema);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->requirePresence('order_now');

        return $validator;
    }

}
