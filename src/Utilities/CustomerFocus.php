<?php

namespace App\Utilities;

use App\Application;
use App\Model\Entity\Customer;
use App\Model\Entity\User;
use App\Model\Table\CustomersTable;
use Cake\Controller\Controller;
use Cake\Http\ServerRequest;
use Cake\Http\Session;
use Cake\ORM\Locator\LocatorAwareTrait;

class CustomerFocus
{
    use LocatorAwareTrait;

    const FOCUS_PATH = 'Focus.customers';

    /**
     * @var ServerRequest
     */
    private ServerRequest $request;
    /**
     * @var Session
     */
    private Session $session;

    public function __construct()
    {
        $this->request = Application::container()->get(ServerRequest::class);
        $this->session = $this->request->getSession();
        $this->makeFocusLookup();
    }

    //<editor-fold desc="CONVENIENCE METHODS">
    protected function request()
    {
        return $this->request;
    }

    protected function getIdentity(): User
    {
        return $this->session->read('Auth');
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
        return $this->request->getData($key, $default);
    }
    //</editor-fold>

    public function refocus(int $customer_id): void
    {
        $entity = $this->getIdentity();
        $entity->set('customer_id', $$customer_id);
    }
    /**
     * Controller/action call to guide admins into a customers focus
     *
     * Use from a controller action:
     * <pre>
     *  if (!$this->CustomerFocus->focus()) {
     *      return $this->render('customersFocus');
     *  }
     * </pre>
     * Assumes a template '`customerFocus`' that allows selection
     * of a customers and posts '`customers_id`'
     *
     * If the logged-in user has a customer id: return true (no other action)
     * If request=POST with '`customer_id`' key in the post: set focus and return true
     * If no focus and not a POST, return false (need to render 'customerFocus' UI)
     *
     * @return bool
     */
    public function focus(Controller $controller): bool
    {
        if (!$this->isAdmin()) {
            return true;
        }

        if ($this->isAdmin() && $this->isFocused()) {
            return true;
        }
        if ($this->request()->is('post') && $this->requestData('customer_id')) {
            $entity = $this->getIdentity();
            $entity->set('customer_id', $this->requestData('customer_id'));
            return true;
        }

        $controller->set('customers', $this->session->read(self::FOCUS_PATH));

        return false;
    }

    public function resetFocusLookup(): void
    {
        $this->session->delete('Focus');
        $this->makeFocusLookup();
    }


    public function lookupFocus(int $id): Customer
    {
        return $this->session->read(self::FOCUS_PATH . '.$id');
    }

    private function makeFocusLookup(): void
    {
        if ($this->session->read(self::FOCUS_PATH)) {
            return;
        }
        $customers = $this->fetchTable('Customers')
            ->find()
            ->all();
        $keyedData = collection($customers)
            ->indexBy(function ($entity) {
                return $entity->id;
            })
            ->toArray();
        $this->session->write(self::FOCUS_PATH,$keyedData);
    }
}
