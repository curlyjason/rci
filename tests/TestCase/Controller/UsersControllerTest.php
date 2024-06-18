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

        foreach (self::ALL_ROLES as $index => $role) {
            $this->login($role);
            $this->get('http://localhost:8015');
//            $this->writeFile("debug$index.html");

            $this->assertResponseCode('200',
                "The user $role was not recognized as a valid, logged-in user");
        }
    }

    public function test_welcomeOmittedWhenNotLoggedIn()
    {
        $this->get('http://localhost:8015/users/forgot-password');

        $this->assertStringNotContainsString('Welcome', $this->_getBodyAsString());

    }

    public function test_welcomeShowsWhenLoggedIn()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login();
        $this->get('http://localhost:8015/');

        $this->assertStringContainsString('Welcome', $this->_getBodyAsString());
    }

    public function test_showLoginLinkWhenNotLoggedIn()
    {
        $this->get('http://localhost:8015/users/forgot-password');
//        $this->writeFile();

        $this->assertResponseRegExp('!href="/?users/login"!',
            'Login link is missing for non-logged in user');
        $this->assertResponseNotRegExp('!href="/?users/logout"!',
            'Logout link is present for non-logged in user');

    }

    public function test_showLogoutLinkWhenLoggedIn()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login();
        $this->get('http://localhost:8015/');
        $this->writeFile();

        $this->assertResponseRegExp('!href=".?users/logout"!',
            'Logout link is missing for logged in user');
        $this->assertResponseNotRegExp('!href=".?users/login"!',
            'Login link is present for logged in user');
    }
    public function test_ForgotPasswordPageRenders()
    {
        $this->get('http://localhost:8015/users/forgot-password');
//        $this->writeFile();

        $this->assertResponseCode('200',
            "The Forgot Password form did not render without errors");

    }

    public function xtest_ForgotPasswordFormClassExecutes()
    {
        $this->post('http://localhost:8015/users/forgot-password');

    }
}
