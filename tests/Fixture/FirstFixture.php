<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FirstFixture
 */
class FirstFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'first';
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
            ],
        ];
        parent::init();
    }
}
