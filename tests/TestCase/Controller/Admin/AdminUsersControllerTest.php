<?php

namespace App\Test\TestCase\Controller\Admin;

use App\Test\Scenario\IntegrationDataScenario;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use App\Test\Traits\MockModelTrait;
use App\Test\Utilities\TestCons;
use Cake\TestSuite\IntegrationTestTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class AdminUsersControllerTest extends \Cake\TestSuite\TestCase
{
    use IntegrationTestTrait;
    use ScenarioAwareTrait;
    use AuthTrait;
    use DebugTrait;
    use MockModelTrait;
    use TruncateDirtyTables;

    public function test_addRendersForAdmins()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login(self::ADMIN_USER);

        $this->get(TestCons::HOST . "/admin/users/add");

        $this->assertResponseCode(200);
    }

    public function test_nonAdminsDisallowed()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login();

        $this->get(TestCons::HOST . "/admin/users/add");

        $this->assertResponseCode(302);

    }

}
