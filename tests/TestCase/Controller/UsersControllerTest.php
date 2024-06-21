<?php

namespace App\Test\TestCase\Controller;

use App\Model\Table\UsersTable;
use App\Test\Scenario\IntegrationDataScenario;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use App\Test\Traits\MockModelTrait;
use App\Test\Utilities\TestCons;
use Cake\TestSuite\IntegrationTestTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class UsersControllerTest extends \Cake\TestSuite\TestCase
{
    use IntegrationTestTrait;
    use ScenarioAwareTrait;
    use AuthTrait;
    use DebugTrait;
    use MockModelTrait;

    public function test_mockALoggedInUser()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);

        foreach (self::ALL_ROLES as $index => $role) {
            $this->login($role);
            $this->get(TestCons::HOST);
//            $this->writeFile("debug$index.html");

            $this->assertResponseCode('200',
                "The user $role was not recognized as a valid, logged-in user");
        }
    }

    public function test_welcomeOmittedWhenNotLoggedIn()
    {
        $this->get(TestCons::HOST . '/users/forgot-password');

        $this->assertStringNotContainsString('Welcome', $this->_getBodyAsString());

    }

    public function test_welcomeShowsWhenLoggedIn()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login();
        $this->get(TestCons::HOST . '/');

        $this->assertStringContainsString('Welcome', $this->_getBodyAsString());
    }

    public function test_showLoginLinkWhenNotLoggedIn()
    {
        $this->get(TestCons::HOST . '/users/forgot-password');

        $this->assertResponseRegExp('!href="/?users/login"!',
            'Login link is missing for non-logged in user');
        $this->assertResponseNotRegExp('!href="/?users/logout"!',
            'Logout link is present for non-logged in user');
    }

    public function test_showLogoutLinkWhenLoggedIn()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login();
        $this->get(TestCons::HOST . '/');
//        $this->writeFile();

        $this->assertResponseRegExp('!href=".?users/logout"!',
            'Logout link is missing for logged in user');
        $this->assertResponseNotRegExp('!href=".?users/login"!',
            'Login link is present for logged in user');
    }

    public function test_ForgotPasswordPageRenders()
    {
        $this->get(TestCons::HOST . '/users/forgot-password');
//        $this->writeFile();

        $this->assertResponseCode('200',
            "The Forgot Password form did not render without errors");

    }

    public function test_forgotPassword_validEmail()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $postData = ['email' => self::USER,];

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/success');
    }

    public function test_forgotPassword_invalidEmail()
    {
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $postData = ['email' => 'bad@email.com',];

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('No user found with that email address');
    }

    public function test_forgotPassword_dbUpdateFailure()
    {
        $this->mockForFailedSave('Users', UsersTable::class, $this->any());
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $postData = ['email' => self::USER,];

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('Database update failed. Please try again');

    }

    public function test_forgotPassword_success()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $postData = ['email' => self::USER,];

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/success');
    }
}
