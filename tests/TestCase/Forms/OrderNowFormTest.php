<?php

namespace App\Test\TestCase\Forms;

use App\Forms\OrderNowForm;
use App\Test\Factory\CustomerFactory;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\Form\Form;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class OrderNowFormTest extends \Cake\TestSuite\TestCase
{
    use ScenarioAwareTrait;
    use TruncateDirtyTables;
    use RetrievalTrait;

    protected Form $Form;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Form = new OrderNowForm();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_executesOnGoodData()
    {
        //<editor-fold desc="PREPARE FIXTURE DATA AND VARIABLES">
        $this->loadFixtureScenario('IntegrationData');
        $customer = $this->getLast('Customers');
        $items = CustomersItemFactory::make()->getTable()
            ->find()
            ->where(['customer_id' => $customer->id])
            ->contain('Items')
            ->all();
        $ids = collection($items->toArray())
            ->indexBy('item_id')
            ->toArray();

        $this->assertCount(3, $ids);
        //</editor-fold>
        $postData = [
            'order_now' => '1',
            'name' => '',
            'email' => 'ddrake@dreamingmind.com',
            'order_quantity' => [
                (int) 0 => '5',
                (int) 1 => '10',
                (int) 2 => '',
            ],
            'id' => array_keys($ids),
        ];

        $result = $this->Form->execute($postData);

        $this->assertTrue($result);
        $this->assertCount(2, $this->Form->getOrderEntity()->order_lines);
    }

}
