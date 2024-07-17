<?php

namespace App\Test\TestCase\Forms;

use App\Forms\NewUserForm;
use App\Test\Factory\CustomerFactory;
use Cake\Form\Form;

class NewUserFormTest extends \Cake\TestSuite\TestCase
{

    protected Form $Form;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Form = new NewUserForm();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    //<editor-fold desc="VALIDATION">
    public function test_validate_goodData()
    {
        $data = [
            'email' => 'false@true.com',
        ];

        $this->assertTrue($this->Form->validate($data));
    }

    public function test_validate_missingEmail()
    {
        $data = [];

        $this->assertFalse($this->Form->validate($data));
        $this->assertContains('`email` is required', $this->Form->getErrors()['email']);
    }

    public function test_validate_emailIsNotAnEmail()
    {
        $data = [
            'email' => 'generic string',
        ];

        $this->assertFalse($this->Form->validate($data));
        $this->assertContains('valid email is required', $this->Form->getErrors()['email']);
    }
    //</editor-fold>

    //<editor-fold desc="SUCCESSFUL EXECUTION">
    public function test_execute_goodWithNewCustomer()
    {
        $expected = [
            'email' => 'false@true.com',
            'customer' => ['name' => 'blafoo',],
        ];

        $customer = CustomerFactory::make()->persist();
        $data = [
            $this->Form::FIELD_EMAIL => 'false@true.com',
            $this->Form::FIELD_CUSTOMER_NEW => 'blafoo', //this will be used
            $this->Form::FIELD_CUSTOMERS_SELECT => $customer->id, //this will be ignored
        ];

        $this->assertTrue($this->Form->execute($data));
        $this->assertEquals($expected, $this->Form->patchData());
    }

    public function test_execute_goodWithSelectedCustomer()
    {
        $customer = CustomerFactory::make()->persist();
        $data = [
            $this->Form::FIELD_EMAIL => 'false@true.com',
            $this->Form::FIELD_CUSTOMERS_SELECT => $customer->id,
        ];

        $expected = [
            'email' => 'false@true.com',
            'customer_id' => $customer->id,
        ];

        $this->assertTrue($this->Form->execute($data));
        $this->assertEquals($expected, $this->Form->patchData());
    }
    //</editor-fold>

    public function test_execute_duplicateEmail()
    {
        $customer = CustomerFactory::make()->withUsers()->persist();
        $data = [
            $this->Form::FIELD_EMAIL => $customer->users[0]->email,
            $this->Form::FIELD_CUSTOMERS_SELECT => $customer->id,
        ];

        $this->assertFalse($this->Form->execute($data));
        $this->assertArrayHasKey('email', $this->Form->getErrors());
        $this->assertArrayHasKey('duplicate', $this->Form->getErrors()['email']);
    }

    public function test_execute_duplicateNewCustomer()
    {
        $customer = CustomerFactory::make()->persist();
        $data = [
            $this->Form::FIELD_EMAIL => 'name@domain.com',
            $this->Form::FIELD_CUSTOMER_NEW => $customer->name,
        ];

        $this->assertFalse($this->Form->execute($data));
        $this->assertArrayHasKey('new_customer', $this->Form->getErrors());
        $this->assertArrayHasKey('duplicate', $this->Form->getErrors()['new_customer']);
    }

    public function test_execute_nonexistentExistingCustomer()
    {
        $data = [
            $this->Form::FIELD_EMAIL => 'name@domain.com',
            $this->Form::FIELD_CUSTOMERS_SELECT => 99,
        ];

        $this->assertFalse($this->Form->execute($data));
        $this->assertArrayHasKey('customers', $this->Form->getErrors());
        $this->assertArrayHasKey('nonexistent', $this->Form->getErrors()['customers']);
    }

}
