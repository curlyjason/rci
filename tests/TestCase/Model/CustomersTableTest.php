<?php

namespace App\Test\TestCase\Model;

use App\Model\Table\CustomersTable;
use App\Test\Factory\CustomerFactory;
use App\Test\Traits\RetrievalTrait;
use App\Utilities\DateUtilityTrait;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Twig\Test\IntegrationTestCase;

class CustomersTableTest extends TestCase
{
    use ScenarioAwareTrait;
    use LocatorAwareTrait;
    use TruncateDirtyTables;
    use RetrievalTrait;

    protected CustomersTable|Table $Customers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Customers = CustomerFactory::make()->getTable();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->Customers);
    }


    public function test_thisMonthsInventoryDate()
    {
        $this->loadFixtureScenario('IntegrationData');
        $customerWithIncompleteInventory = $this->getFirst('Customers');

        $this->assertEmpty($this->Customers->withIncompleteInventory()->toArray(),
            'fixture data shouldn\'t have any customers with pending inventory');

        $modified = (new DateTime())
            ->firstOfMonth()
            ->modify('first day of last month')
            ->format('Y-m-d 00:00:01');
        $customerWithIncompleteInventory->set('modified', $modified);
        $this->Customers->save($customerWithIncompleteInventory);

        $result = $this->Customers->withIncompleteInventory()->toArray();

        $this->assertCount(1, $result,
            'Company with modified date before 1st of this month not found');
        $this->assertNotEmpty($result[0]->users,
            'no user records were contained in the result');
    }

    protected function getFixturesDir()
    {
        // TODO: Implement getFixturesDir() method.
    }
}
