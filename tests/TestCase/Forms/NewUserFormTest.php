<?php

namespace App\Test\TestCase\Forms;

use App\Forms\NewUserForm;
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

    public function test_execute()
    {
        $data = [
            $this->Form::FIELD_EMAIL => 'false@true.com',
            $this->Form::FIELD_CUSTOMER_NEW => 'blafoo',
            $this->Form::FIELD_CUSTOMERS_SELECT => '',
        ];

        $this->assertTrue($this->Form->execute($data));
        debug($this->Form);
    }
}
