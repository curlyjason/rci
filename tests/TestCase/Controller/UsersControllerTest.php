<?php

namespace App\Test\TestCase\Controller;

use App\Forms\ResetPasswordForm;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\Scenario\IntegrationDataScenario;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use App\Test\Traits\MockModelTrait;
use App\Test\Utilities\TestCons;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;
    use ScenarioAwareTrait;
    use AuthTrait;
    use DebugTrait;
    use MockModelTrait;
    use TruncateDirtyTables;
    use EmailTrait;

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

    //<editor-fold desc="UX FEATURES">
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
    //</editor-fold>

    //<editor-fold desc="FORGOT-PASSWORD PAGE">
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
        $postData = ['email' => $this->getUser()->email,];
        $resetPasswordNotificationEvent = $this->createMock(Event::class);

        $this->containerServices = [Event::class => $resetPasswordNotificationEvent] ;

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/success');
        $this->assertResponseCode(302);

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
        $postData = ['email' => $this->getUser()->email,];

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('Database update failed. Please try again');

    }

    public function test_forgotPassword_success()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $postData = ['email' => $this->getUser()->email,];

        $this->post(TestCons::HOST . '/users/forgot-password', $postData);

        $this->assertFlashElement('flash/success');
    }
    //</editor-fold>

    //<editor-fold desc="RESET-PASSWORD PAGE">
    public function test_resetPasswordPageRenders()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $user = $this->getUser();

        $this->get($this->getValidResetPasswordEndpoint($user));
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

        $this->get($this->getValidResetPasswordEndpoint($user));

        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('The link has expired. Please request another.');
    }

    public function test_resetPassword_badPostData()
    {
        $form = $this->createMock(ResetPasswordForm::class);
        $form->expects($this->once())->method('execute')->willReturn(false);
        $form->expects($this->any())->method('getErrors')->willReturn(['confirm_password' => 'Mismatch Error',]);
        $this->containerServices = [ResetPasswordForm::class => $form];

        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $user = $this->getUser();
//        $postData = ['new_password' => 'string', 'confirm_password' => 'other string'];

//        debug('relevant');
        $this->post($this->getValidResetPasswordEndpoint($user));
//        $this->writeFile();

        $this->assertResponseCode('200');
        $this->assertResponseRegExp('/Mismatch Error/');
//        $this->assertResponseRegExp('/Passwords do not match/');
    }

    public function test_resetPassword_goodPostData()
    {
        $form = $this->createMock(ResetPasswordForm::class);
        $form->expects($this->any())->method('execute')->willReturn(true);
        $form->expects($this->any())->method('getErrors')->willReturn([]);
        $this->containerServices = [ResetPasswordForm::class => $form];

        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $user = $this->getUser();

        $postData = ['new_password' => 'string'];
        $this->post($this->getValidResetPasswordEndpoint($user), $postData);
//        $this->writeFile();

        $this->assertResponseCode('302');
        $this->assertFlashElement('flash/success');
        $this->assertFlashMessage('Password reset, please log in');
    }

    public function test_resetPassword_goodPostData_failedSave()
    {
        $form = $this->createMock(ResetPasswordForm::class);
        $form->expects($this->any())->method('execute')->willReturn(true);
        $form->expects($this->any())->method('getErrors')->willReturn([]);
        $this->containerServices = [ResetPasswordForm::class => $form];

        $this->mockForFailedSave('Users', UsersTable::class, $this->any());

        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $user = $this->getUser();

        $postData = ['new_password' => 'string'];
        $this->post($this->getValidResetPasswordEndpoint($user), $postData);
//        $this->writeFile();

        $this->assertResponseCode('200');
        $this->assertFlashElement('flash/error');
        $this->assertFlashMessage('Password did not save, please try again.');
    }
    //</editor-fold>

    //<editor-fold desc="PRIVATE CONVENIENCE METHODS">
    private function setExpiredModifiedDate(User $user): bool|\Cake\Datasource\EntityInterface
    {
        $user->set('modified', FrozenTime::now()->modify('-1 day -10 minutes'));

        return $this->fetchTable('Users')
            ->save($user);
    }

    /**
     * @param mixed $user
     * @return string
     */
    private function getValidResetPasswordEndpoint(mixed $user): string
    {
        $validResetPasswordEndpoint = TestCons::HOST . "/users/reset-password/{$user->email}/{$user->getDigest()}";
        return $validResetPasswordEndpoint;
    }
    //</editor-fold>

}
