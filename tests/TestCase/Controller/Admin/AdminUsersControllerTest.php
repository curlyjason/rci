<?php

namespace App\Test\TestCase\Controller\Admin;

use App\Forms\NewUserForm;
use App\Model\Table\UsersTable;
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

    public function setUp(): void
    {
        parent::setUp();
    }
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->_session);
    }

    //<editor-fold desc="PAGE ACCESS">

    public function test_nonAdminsDisallowed()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login();

        $this->get($this->getUrl());
//        $this->writeFile();

        $this->assertResponseCode(302);

    }
   public function test_addRendersForAdmins()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login(self::ADMIN_USER);

        $this->get($this->getUrl());

        $this->assertResponseCode(200);
    }
    //</editor-fold>

    //<editor-fold desc="POSTED DATA OUTCOMES">
    public function test_successfullyPostAndSaveNewUserData()
    {
        $form = $this->createMock(NewUserForm::class);
        $form->expects($this->once())->method('execute')->willReturn(true);
        $this->containerServices[NewUserForm::class] = $form;
        $this->mockForSave('Users', UsersTable::class, $this->any());
        $postData = ['email' => 'name@host.com', 'password' => 'password'];

        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login(self::ADMIN_USER);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();

        $this->post($this->getUrl(), $postData);

        $this->assertResponseCode(302);
        $this->assertFlashMessage('The user has been saved.');

    }

    public function test_badFormData()
    {
        $form = $this->createMock(NewUserForm::class);
        $form->expects($this->once())->method('execute')->willReturn(false);
        $this->containerServices[NewUserForm::class] = $form;
        $postData = [];

        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login(self::ADMIN_USER);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();

        $this->post($this->getUrl(), $postData);

        $this->assertResponseCode(200);
//        $this->assertFlashMessage('add the Form class message here');
    }

    public function test_patchEntityError()
    {
        $form = $this->createMock(NewUserForm::class);
        $form->expects($this->once())->method('execute')->willReturn(true);
        $this->containerServices[NewUserForm::class] = $form;
        $this->mockForFailedSave('Users', UsersTable::class, $this->any());

        $postData = ['email' => 'name@host.com', 'password' => null];//password required

        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $this->login(self::ADMIN_USER);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();

        $this->post($this->getUrl(), $postData);
//        $this->writeFile();

        $this->assertResponseCode(200);
        $this->assertResponseRegExp('/correct the input errors/');

    }
    //</editor-fold>

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return TestCons::HOST . "/admin/users/add";
    }

}
