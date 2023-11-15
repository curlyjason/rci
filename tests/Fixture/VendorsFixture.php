<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VendorsFixture
 */
class VendorsFixture extends TestFixture
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
                'name' => '',
                'created' => '2023-11-14 18:56:55',
                'modified' => '2023-11-14 18:56:55',
            ],
        ];
        parent::init();
    }
}
