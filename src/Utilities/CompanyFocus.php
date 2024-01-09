<?php

namespace App\Utilities;

use App\Application;
use App\Model\Entity\User;
use App\Model\Table\CustomersTable;
use Cake\Controller\Controller;
use Cake\Http\ServerRequest;
use Cake\Http\Session;
use Cake\ORM\Locator\LocatorAwareTrait;

class CompanyFocus
{
    use LocatorAwareTrait;

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

    public function refocus(int $company_id): void
    {
        $entity = $this->getIdentity();
        $entity->set('customer_id', $$company_id);
    }
    /**
     * Controller/action call to guide admins into a company focus
     *
     * Use from a controller action:
     * <pre>
     *  if (!$this->CompanyFocus->focus()) {
     *      return $this->render('companyFocus');
     *  }
     * </pre>
     * Assumes a template '`companyFocus`' that allows selection
     * of a company and posts '`company_id`'
     *
     * If the logged-in user has a company id: return true (no other action)
     * If request=POST with '`company_id`' key in the post: set focus and return true
     * If no focus and not a POST, return false (need to render 'companyFocus' UI)
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

        $table = $this->fetchTable('Customers');
        /* @var CustomersTable $table */
        $customers = $table->find()->all();
        $controller->set(compact('customers'));

        return false;
    }
}
