<?php

namespace App\Test\TestCase\Model;

use App\Model\Table\CustomersItemsTable;
use App\Model\Table\CustomersTable;
use App\Test\Factory\CustomerFactory;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CustomersItemsTableTest extends \Cake\TestSuite\TestCase
{
    use ScenarioAwareTrait;
    use LocatorAwareTrait;
    use TruncateDirtyTables;
    use RetrievalTrait;

    protected CustomersItemsTable|Table $CustomersItems;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CustomersItems = CustomersItemFactory::make()->getTable();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->CustomersItems);
    }

    public function test_beforeSaveEventInsuresFieldIsSet_nextInventory()
    {
        $ci = CustomersItemFactory::make()
            ->withCustomers()
            ->withItems()
            ->persist();

        $this->assertNull($ci->next_inventory,
            'fixture bakes with data-to-be-tested by default, next assertion will be moot');

        $ci = CustomersItemFactory::make()
            ->withCustomers()
            ->withItems()
            ->listeningToModelEvents('Model.beforeSave')->persist();

        $this->assertIsString($ci->next_inventory,
            'no value was set for CustomerItem->next_inventory on record creation');
    }

}
