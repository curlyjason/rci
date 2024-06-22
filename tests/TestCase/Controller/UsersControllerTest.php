<?php

namespace App\Test\TestCase\Controller;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\Scenario\IntegrationDataScenario;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use App\Test\Traits\MockModelTrait;
use App\Test\Utilities\TestCons;
use Cake\I18n\FrozenTime;
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

    public function test_resetPasswordPageRenders()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $user = $this->getUser();

        $this->get(TestCons::HOST . "/users/reset-password/{$user->email}/{$user->getDigest()}");
//        $this->writeFile();

        $this->assertResponseCode('200',
            "The Reset Password form did not render without errors");

    }

    public function test_resetPassword_invalidEmailArg()
    {
        $this->enableRetainFlashMessages();
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $user = $this->getUser();

        $this->get(TestCons::HOST . "/users/reset-password/{$user->email}/string");

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('The chosen user is not valid.');
    }

    public function test_resetPassword_invalidHashArg()
    {
        $this->enableRetainFlashMessages();

        $this->get(TestCons::HOST . "/users/reset-password/me@bad.com/string");

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('The chosen user does not exist.');
    }

    public function test_resetPassword_expiredLink()
    {
        $this->enableRetainFlashMessages();
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $user = $this->setExpiredModifiedDate($this->getUser());

        $this->get(TestCons::HOST . "/users/reset-password/{$user->email}/{$user->getDigest()}");

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('The link has expired. Please request another.');
    }

    /**
     * @return mixed
     */
    private function getUser(): mixed
    {
        $user = $this->fetchTable('Users')
            ->findByEmail(self::USER)
            ->first();
        return $user;
    }

    private function setExpiredModifiedDate(User $user): bool|\Cake\Datasource\EntityInterface
    {
        $modified = new FrozenTime(time() - 60 * 60 * 24 * 1.1);
        $user->set('modified', $modified);

        return $this->fetchTable('Users')
            ->save($user);
    }

}
