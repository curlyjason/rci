<?php

namespace App\Forms;

use AllowDynamicProperties;
use App\Model\Table\CustomersTable;
use App\Model\Table\UsersTable;
use Cake\Event\EventManager;
use Cake\Form\Schema;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class NewUserForm extends \Cake\Form\Form
{

    //<editor-fold desc="FIELD NAME CONSTANTS">
    const FIELD_EMAIL = 'email';
    const FIELD_CUSTOMER_ID = 'customer_id';
    const FIELD_CUSTOMER = 'customer';
    const FIELD_NAME = 'name';
    const FIELD_CUSTOMERS_SELECT = 'customers';
    const FIELD_CUSTOMER_NEW = 'new_customer';
    //</editor-fold>

    protected $patchData = [];
    protected CustomersTable|Table $Customers;
    protected UsersTable|Table $Users;

    use LocatorAwareTrait;

    //<editor-fold desc="CLASS CONFIGURATION">
    public function __construct(?EventManager $eventManager = null)
    {
        parent::__construct($eventManager);
        $this->Customers = $this->fetchTable('Customers');
        $this->Users = $this->fetchTable('Users');
    }

    public function setSchema(Schema $schema)
    {
        $schema
            ->addField('email', ['type' => 'text'])
            ->addField('new_customer', ['type' => 'text'])
            ->addField('customers', ['type' => 'text']);

        return parent::setSchema($schema);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(self::FIELD_EMAIL, 'create', '`email` is required')
            ->email(self::FIELD_EMAIL, false, 'valid email is required');

        return parent::validationDefault($validator);
    }
    //</editor-fold>

    public function execute(array $data, array $options = []): bool
    {
        return parent::execute($data, $options)
            && $this->prepareEmail($data)
            && $this->evaluateCompanyName($data);
    }

    private function evaluateCompanyName(array $data): bool
    {
        return !empty($data[self::FIELD_CUSTOMER_NEW])
        ? $this->prepareNewCompany($data)
        : $this->prepareLinkedCompany($data);
    }

    //<editor-fold desc="PATCH DATA/ERROR MESSAGE PREPARATION">
    private function prepareNewCompany(mixed $data): bool
    {
        $foundCustomer = $this->Customers->findByName($data[self::FIELD_CUSTOMER_NEW])->first();
        if(is_null($foundCustomer)) {
            $this->patchData[self::FIELD_CUSTOMER]
                = [self::FIELD_NAME => $data[self::FIELD_CUSTOMER_NEW]];
            $result = true;
        }
        else {
            $this->_errors[self::FIELD_CUSTOMER_NEW] = ['duplicate' => 'New customers must be unique'];
            $result = false;
        }
        return $result;
    }

    private function prepareLinkedCompany(mixed $data): bool
    {
        $foundCustomer = $this->Customers->findByName($data[self::FIELD_CUSTOMERS_SELECT])->first();
        if(!is_null($foundCustomer)) {
            $this->patchData[self::FIELD_CUSTOMER_ID] = $data[self::FIELD_CUSTOMERS_SELECT];
            $result = true;
        }
        else {
            $this->_errors[self::FIELD_CUSTOMER_NEW] = ['nonexistent' => 'Select a customer for this user'];
            $result = false;
        }
        return $result;
    }

    private function prepareEmail(array $data): bool
    {
//        if(!is_null($this->Customers->findByName($data[self::FIELD_CUSTOMERS_SELECT]))) {
//            $this->patchData[self::FIELD_CUSTOMER_ID] = $data[self::FIELD_CUSTOMERS_SELECT];
//            $result = true;
//        }
//        else {
//            $this->_errors[self::FIELD_CUSTOMER_NEW] = ['nonexistent' => 'Select a customer for this user'];
//            $result = false;
//        }
//        return $result;
        $this->patchData[self::FIELD_EMAIL] = $data[self::FIELD_EMAIL];
        return true;
    }
    //</editor-fold>

    public function patchData()
    {
        return $this->patchData;
    }

}
