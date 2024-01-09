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

    /**
     * Set request, session, and current focus look-up
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
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
    //</editor-fold>

    /**
     * Controller/action call to guide admins into a customers focus
     *
     * Use from a controller action:
     * <pre>
     *  if (!$this->CustomerFocus->focus($this)) {
     *      return $this->render('customersFocus');
     *  }
     * </pre>
     * Assumes a template '`customerFocus`' that allows selection
     * of a customer and posts the id on key '`customers_id`'
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

    /**
     * Set focus for the logged-in admin
     *
     * @param int $customer_id
     * @return void
     */
    public function setFocus(int $customer_id): void
    {
        if(!$this->getIdentity()->isAdmin()) {
            return;
        }
        $entity = $this->getIdentity();
        $entity->set('customer_id', $customer_id);
    }

    /**
     * Make the logged-in admin lose focus
     * @return void
     */
    public function blur(): void
    {
        if(!$this->getIdentity()->isAdmin()) {
            return;
        }
        $entity = $this->getIdentity();
        $entity->set('customer_id', null);
    }

    /**
     * Get the entity currently in focus
     *
     * @param int $id
     * @return Customer
     */
    public function lookupFocus(int $id): Customer
    {
        return $this->session->read(self::FOCUS_PATH . '.$id');
    }

    /**
     * Called by event to keep data current
     * @return void
     */
    public function resetFocusLookup(): void
    {
        $this->session->delete('Focus');
        $this->makeFocusLookup();
    }
}
