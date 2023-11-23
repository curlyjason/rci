<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Model\Table\CustomersItemsTable;
use Cake\I18n\DateTime;
use Cake\Utility\Text;

class CustomersItemsController extends ApiController
{

    protected string|array $allowed_methods;
    /**
     * @var CustomersItemsTable
     */
    protected mixed $CustomersItems;

    public function initialize(): void
    {
        parent::initialize();
        $this->CustomersItems = $this->fetchTable('CustomersItems');
        $this->allowed_methods = ['post', 'patch', 'get'];
    }

    public function setInventory()
    {
        if ($this->request->is($this->allowed_methods)) {
            $response = $this->updateInventory();
        }
        else {
            $response = $this->badRequestMethod();
        }
        $this->set(compact('response'));
    }

    public function setTrigger()
    {
        if ($this->request->is($this->allowed_methods)) {
            $response = ['set trigger', 'item', 'value'];
        }
        else {
            $response = $this->badRequestMethod();
        }
        $this->set(compact('response'));
    }

    public function orderItem()
    {
        if ($this->request->is($this->allowed_methods)) {
            $response = ['order item', 'item', 'qty'];
            $result2 = ['order item', 'item', 'qty'];
            $result3 = ['order item', 'item', 'qty'];
            $this->set(compact('result2', 'result3'));
        }
        else {
            $response = $this->badRequestMethod();
        }
        $this->set(compact('response'));
    }

    /**
     * @return \stdClass
     */
    private function badRequestMethod(): \stdClass
    {
        $method = Text::toList($this->allowed_methods, 'or');
        $response = new \stdClass();
        $response->error = "Only $method operations are allowed. Your system access call has been logged for review.";
        return $response;
    }

    /**
     * @return string[]
     */
    protected function updateInventory(): object
    {
        try {
            $entity = $this->CustomersItems->get($this->request->getdata('id'));
            $this->CustomersItems->patchEntity($entity, [
                'quantity' => $this->request->getData('quantity'),
                'next_inventory' => (new DateTime())
                    ->firstOfMonth()
                    ->modify('first day of next month')
                    ->format('Y-m-d 00:00:01'),
            ]);

            if ($this->CustomersItems->save($entity)) {
                $response = $entity;
            }
            else {
                $response = new \stdClass();
                $response->error = $entity->getErrors();
            }
        } catch (\Exception $e) {
            $response = $this->encodeExceptionForJson($e);
        }

        return $response;
    }
}
