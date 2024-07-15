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

    public function test_execute()
    {
        $data = [
//            'email' => false,
            'new_customer' => false,
            'customers' => false,
        ];


        debug($this->Form->validate($data));
        debug($this->Form->getErrors());
        $this->assertTrue($this->Form->execute([]));
    }
}
