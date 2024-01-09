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
    //</editor-fold>

    public function refocus(int $company_id): void
    {
        $entity = $this->getIdentity();
        $entity->set('customer_id', $$company_id);
    }
    /**
     * Guide admins into a company focus
     *
     * Use:
     * <pre>
     *  if (!$this->CompanyFocus->focus()) {
     *      return $this->render('companyFocus');
     *  }
     * </pre>
     * Assumes a template '`companyFocus`' that allows selection
     * of a company and posts '`company_id`'
     *
     * If the logged-in user has a company id, we return true
     *
     *
     * @return bool
     */
    public function focus()
    {
        if (!$this->isAdmin()) {
            return true;
        }

        if ($this->isAdmin() && $this->isFocused()) {
            return true;
        }

        if ($this->request()->is('post') && $this->requestData('company_id')) {
            $entity = $this->getIdentity();
            $entity->set('customer_id', $this->requestData('company_id'));
            return true;
        }

        $table = $this->fetchTable('Customers');
        /* @var CustomersTable $table */
        $customers = $table->find()->all();
        $this->getController()->set(compact('customers'));

        return false;
    }
}
