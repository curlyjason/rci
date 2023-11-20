<?php

namespace App\Controller\Api;

use App\Controller\ApiController;

class CustomersItemsController extends ApiController
{

    protected string|array $allowed_methods;

    public function initialize(): void
    {
        parent::initialize();
        $this->allowed_methods = ['patch', 'get'];
    }

    public function setInventory()
    {
        if ($this->request->is($this->allowed_methods)) {
            $response = ['set inventory', 'item', 'value'];
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
        $response = new \stdClass();
        $response->error = 'Only patch operations are allowed. Your system access call has been logged for review.';
        return $response;
    }
}
