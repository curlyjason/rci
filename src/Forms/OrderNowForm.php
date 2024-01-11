<?php

namespace App\Forms;

use Cake\Form\Form;
use Cake\Form\Schema;

class OrderNowForm extends Form
{

    public array $posted = [
        'order_now' => '1',
        'order_quantity' => [
            0 => '0',
            1 => '',
            2 => '7',
            3 => '',
        ],
        'id' => [
            0 => '291608622',
            1 => '291608625',
            2 => '291608628',
            3 => '1638876896',
        ],
    ];

    protected function _buildSchema(Schema $schema): Schema
    {
        parent::_buildSchema($schema);
        return $schema->addField('name', 'string')
            ->addField('order_now', ['type' => 'bool'])
            ->addField('order_quantity', ['type' => 'array'])
            ->addField('id', ['type' => 'array']);

    }

    public function validate(array $data, ?string $validator = null): bool
    {
        return parent::validate($data, $validator);
    }

    public function execute(array $data, array $options = []): bool
    {
        return parent::execute($data, $options);
    }
}
