<?php

namespace App\Forms;

use App\Model\Table\ItemsTable;
use App\Model\Table\OrderLinesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validator;

class OrderNowForm extends Form
{
    use LocatorAwareTrait;

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
            /**
             * @var ItemsTable $ITable
             * @var OrderLinesTable $OLTable
             */
            $order = $OLTable->Orders->newEntity([
                'order_number' => uniqid(),
                'ordered_by' => $this->getData('name'),
                'ordered_by_email' => $this->getData('email'),
                'status' => 'new',
                'order_date' => (new FrozenTime(time()))->format('Y-m-d'),
                'order_lines' => [],
            ]);
            foreach ($this->getData('order_quantity') as $index => $qty) {
                if ($qty) {
                    $itemId = $this->getData('id')[$index];
                    $entity = $OLTable->newEntity($ITable->get($itemId)->toArray());
                    $entity->set('quantity', $qty);
                    $order['order_lines'][] = $entity;
                }
            }
            $OLTable->Orders->checkRules($order);
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
