<?php

namespace App\Test\TestCase\Model;

use App\Model\Entity\CustomersItem;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CustomersItemTest extends \Cake\TestSuite\TestCase
{
    use TruncateDirtyTables;
    use RetrievalTrait;

    protected CustomersItem|EntityInterface $Entity;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @dataProvider inventory
     *
     * @return void
     */
    public function test_this($eData, $result)
    {
        $entity = $this->getCustomersItemEntity($eData);
        $this->assertEquals($result, $entity->orderAmount());

    }

    public static function inventory()
    {
        return [
            [['quantity' => 5, 'target_quantity' => 10], 5],
            [['quantity' => 10, 'target_quantity' => 10], 0],
            [['quantity' => 15, 'target_quantity' => 10], 0],
        ];
    }

    /**
     * @param array $data
     * @return void
     */
    private function getCustomersItemEntity(array $data): CustomersItem|EntityInterface
    {
        return CustomersItemFactory::make($data)
            ->withItems()
            ->withCustomers()
            ->getEntity();
    }
}
