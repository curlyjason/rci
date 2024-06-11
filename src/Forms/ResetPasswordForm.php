<?php


namespace App\Forms;


use Cake\Form\Form;
use Cake\Form\Schema;

class ResetPasswordForm extends Form
{
    /**
     * Execute the form if it is valid.
     *
     * First validates the form, then calls the `_execute()` hook method.
     * This hook method can be implemented in subclasses to perform
     * the action of the form. This may be sending email, interacting
     * with a remote API, or anything else you may need.
     *
     * @param array $data Form data.
     * @return bool False on validation failure, otherwise returns the
     *   result of the `_execute()` method.
     */
    public function execute(array $data, $options = []): bool
    {
        if ($data['new_password'] === $data['confirm_password']) {
            $result = true;
        }
        else {
            $this->setErrors(['confirm_password' => 'Passwords do not match']);
            $result = false;
        }
        return $result;
    }

    /**
     * A hook method intended to be implemented by subclasses.
     *
     * You can use this method to define the schema using
     * the methods on Cake\Form\Schema, or loads a pre-defined
     * schema from a concrete class.
     *
     * @param \Cake\Form\Schema $schema The schema to customize.
     * @return \Cake\Form\Schema The schema to use.
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('new_password', ['type' => 'string'])
            ->addField('confirm_password', ['type' => 'string']);
    }

}
