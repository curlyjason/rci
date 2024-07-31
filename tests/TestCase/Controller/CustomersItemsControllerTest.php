<?php

namespace App\Test\TestCase\Controller;

use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use App\Test\Traits\MockModelTrait;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\IntegrationTestTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class CustomersItemsControllerTest extends \Cake\TestSuite\TestCase
{
    use IntegrationTestTrait;
    use ScenarioAwareTrait;
    use AuthTrait;
    use DebugTrait;
    use MockModelTrait;
    use TruncateDirtyTables;
    use EmailTrait;

    public function test_takeInventoryRenders()
    {
        $this->loadFixtureScenario('IntegrationData');
        $this->login();

        $this->get('take-inventory');
//        $this->writeFile();

        $this->assertResponseCode(200);
    }

    public function test_setTriggerLevelsRenders()
    {
        $this->loadFixtureScenario('IntegrationData');
        $this->login();

        $this->get('set-trigger-levels');
//        $this->writeFile();

        $this->assertResponseCode(200);
    }

    public function test_orderNowRenders()
    {
        $this->loadFixtureScenario('IntegrationData');
        $this->login();

        $this->get('order-now');
//        $this->writeFile();

        $this->assertResponseCode(200);
    }
}
