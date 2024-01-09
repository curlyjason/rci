<?php

namespace App\Controller\Component;

use App\Model\Entity\User;
use App\Model\Table\CustomersTable;
use Cake\Datasource\EntityInterface;
use Cake\Http\Session;
use Cake\ORM\Locator\LocatorAwareTrait;

class CompanyFocusComponent extends \Cake\Controller\Component
{
    use LocatorAwareTrait;

    protected function request()
    {
        return $this->getController()->getRequest();
    }

    protected function getIdentity(): User
    {
        return $this->getController()->getRequest()->getSession()->read('Auth');
    }

    protected function isAdmin(): bool
    {
        return $this->getIdentity()->isAdmin();
    }

    protected function isFocused(): bool
    {
        return (bool) $this->getFocus();
    }

    protected function getFocus(): ?int
    {
        return $this->getIdentity()?->customer_id;
    }

    protected function requestData(?string $key = null, mixed $default = null): mixed {
        return $this->request()->getData($key, $default);
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
        /* @var CustomersTable $table */

        if ($this->request()->is('post')) {
            $entity = $this->getIdentity();
            $table->Users->patchEntity(
                $entity,
                ['customer_id' => $this->requestData('customer_id')]
            );
            if (!$table->Users->save($entity)) {
                /**
                 * Save failed. remove customer id for entity (reference points into session)
                 */
                $table->Users->patchEntity($entity, ['customer_id' => '']);
                $this->getController()
                    ->Flash
                    ->error('The customer id could not be set on the user');
                return false;
            }
            return true;
        }

        $customers = $table->find()->all();
        $this->getController()->set(compact('customers'));

        return false;
    }
}
