<?php

namespace App\Test\TestCase\Utilities;

use App\Model\Entity\CustomersItem;
use App\Model\Entity\User;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Factory\UserFactory;
use App\Test\Fixture\FixtureStructureStandard;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\RetrievalTrait;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\DateUtilityTrait;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\IntegrationTestTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CustomerInventoryStatusReporterTest extends \Cake\TestSuite\TestCase
{
    use ScenarioAwareTrait;
    use TruncateDirtyTables;
    use RetrievalTrait;
    use DateUtilityTrait;

    protected $DataStructure;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtureScenario('IntegrationData');
        $this->DataStructure = new mockReporter($this->getLast('Customers'));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->DataStructure);
    }

    public function test_getNewOrderPost_checkKeys()
    {
        $this->makeInventoryComplete();
        $user = $this->getLast('Users');

        $result = $this->DataStructure->getNewOrderPost($user);

        FixtureStructureStandard::assertKeysMatch_orderNow($result,
            'Order POST-array keys don\'t match keys in the standard reference array');
    }

    protected function makeInventoryComplete()
    {
        $items = $this->getRecords('CustomersItems');
        /* @var CustomersItem $item */
        $updatedItems = collection($items)
            ->map(function ($item) {
                return $item->set('next_inventory', $this->nextMonthsInventoryDate());
            })
            ->toArray();
        CustomersItemFactory::make()->getTable()->saveMany($updatedItems);
        $this->DataStructure = new CustomerInventoryStatusReporter($this->getLast('Customers'));
    }

    public function test_lastNoticeWas()
    {
        //array of last_notice strings with regex
        $notices = [
            '' => '^$',
            'anythingPrompt' => '(?i)prompt$',
        ];

        foreach ($notices as $literal => $pattern) {
            $this->DataStructure->customer()->set('last_notice', $literal);
            $this->assertTrue($this->DataStructure->lastNoticeWas($pattern));
        }
    }

    public function test_chooseNotification()
    {
        $this->DataStructure->customer()->set('last_inventory_notification', $this->DataStructure->lastMonthsInventoryDate());
        $this->DataStructure->chooseNotification();
        $this->assertTrue(true);
    }
}

class mockReporter extends CustomerInventoryStatusReporter {
    public function __construct($customer)
    {
        parent::__construct($customer);
    }

    public function lastNoticeWas(string $notice) : bool
    {
        return parent::lastNoticeWas($notice);
    }
}
