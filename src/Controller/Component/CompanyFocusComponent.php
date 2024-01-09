<?php

namespace App\Controller\Component;

use App\Model\Entity\User;
use Cake\Datasource\EntityInterface;
use Cake\Http\Session;
use Cake\ORM\Locator\LocatorAwareTrait;

class CompanyFocusComponent extends \Cake\Controller\Component
{
    use LocatorAwareTrait;

    public function getIdentity(): User
    {
        return $this->getController()->getRequest()->getSession()->read('Auth');
    }

    public function isAdmin(): bool
    {
        return $this->getIdentity()->isAdmin();
    }

    public function isFocused(): bool
    {
        return (bool) $this->getFocus();
    }

    public function getFocus(): ?int
    {
        return $this->getIdentity()?->customer_id;
    }

    public function requestData(?string $key = null, mixed $default = null): mixed {
        return $this->getController()->getRequest()->getData($key, $default);
    }

    public function focus()
    {
        if (!$this->isAdmin()) {
            return true;
        }

        if ($this->isAdmin() && $this->isFocused()) {
            return true;
        }

        $table = $this->fetchTable('Customers');

        if ($this->getController()->getRequest()->is('post')) {
            $entity = $this->getIdentity();
            $table->patchEntity($entity, ['customer_id' => $this->requestData('customer_id')]);
            if (!$table->Users->save($entity)) {
                $this->getController()->Flash->error('The customer id could not be set on the user');
                return false;
            }
            return true;
        }

        $customers = $table->find()->all();
        $this->getController()->set(compact('customers'));

        return false;
    }
}
