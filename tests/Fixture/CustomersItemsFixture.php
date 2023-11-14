<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CustomersItemsFixture
 */
class CustomersItemsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'quantity' => 1,
                'target_quantity' => 1,
                'customer_id' => 1,
                'item_id' => 1,
                'created' => '2023-11-14 18:56:55',
                'modified' => '2023-11-14 18:56:55',
            ],
        ];
        parent::init();
    }
}
