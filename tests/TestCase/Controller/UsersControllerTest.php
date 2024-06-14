<?php

namespace App\Test\TestCase\Controller;

use App\Test\Scenario\IntegrationDataScenario;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use Cake\TestSuite\IntegrationTestTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class UsersControllerTest extends \Cake\TestSuite\TestCase
{
    use IntegrationTestTrait;
    use ScenarioAwareTrait;
    use AuthTrait;
    use DebugTrait;

    public function test_mockALoggedInUser()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);

        foreach (self::ALL_ROLES as $role) {
            $this->login($role);
            $this->get('http://localhost:8015');

            $this->assertResponseCode('200',
                "The user $role was not recognized as a valid, logged-in user");
        }
    }

    public function test_ForgotPasswordPageRenders()
    {
        $this->get('http://localhost:8015/users/forgot-password');
        $this->writeFile();

        $this->assertResponseCode('200',
            "The Forgot Password form did not render without errors");

    }

    public function test_ForgotPasswordFormClassExecutes()
    {
        $this->post('http://localhost:8015/users/forgot-password');

    }
}
