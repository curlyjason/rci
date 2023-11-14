<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrdersFixture
 */
class OrdersFixture extends TestFixture
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
                'order_number' => '',
                'ordered_by' => '',
                'ordered_by_email' => '',
                'status' => '',
                'order_date' => '2023-11-14',
                'due_date' => '2023-11-14',
                'ship_date' => '2023-11-14',
                'created' => '2023-11-14 18:56:55',
                'modified' => '2023-11-14 18:56:55',
            ],
        ];
        parent::init();
    }
}
