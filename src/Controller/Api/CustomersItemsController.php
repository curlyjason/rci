<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Model\Table\CustomersItemsTable;
use App\Utilities\DateUtilityTrait;
use App\Utilities\UserMailer;
use Cake\I18n\DateTime;
use Cake\Utility\Text;
use Couchbase\User;

class CustomersItemsController extends ApiController
{
    use DateUtilityTrait;

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
            $response = $this->updateTriggerValue();
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
                'next_inventory' => $this->nextMonthsInventoryDate(),
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

    /**
     * @return string[]
     */
    protected function updateTriggerValue(): object
    {
        try {
            $entity = $this->CustomersItems->get($this->request->getdata('id'));
            $this->CustomersItems->patchEntity($entity, [
                'target_quantity' => $this->request->getData('target_quantity'),
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

    public function sendReplenishInventory()
    {
        $mailer = new UserMailer('default');
        $mailer
            ->setEmailFormat('text')
            ->setFrom(['jason@curlymedia.com' => 'Curly Media'])
            ->setTo('jason@tempestinis.com')
            ->setSubject('About');
        osd($mailer);
        $mailer
            ->deliver('My message');
    }
}
