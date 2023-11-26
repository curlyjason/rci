<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'email' => '',
                'password' => '',
                'created' => '2023-11-26 04:07:14',
                'modified' => '2023-11-26 04:07:14',
            ],
        ];
        parent::init();
    }
}
