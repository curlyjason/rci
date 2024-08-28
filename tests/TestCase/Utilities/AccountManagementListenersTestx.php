<?php

namespace App\Test\TestCase\Utilities;

use App\Constants\CISRCon;
use App\Constants\EmailCon;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\Scenario\CustomerInventoryStatusReporterScenario;
use App\Test\Scenario\IntegrationDataScenario;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\NotificationListeners;
use Cake\Event\Event;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\TestEmailTransport;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class AccountManagementListenersTestx extends TestCase
{

    use TruncateDirtyTables;
    use EmailTrait;
    use ScenarioAwareTrait;

    /**
     * @param $event Event
     */
    public function test_newAccountNotification()
    {
        $this->markTestSkipped();
    }

    public function test_resetPasswordNotification_forgot()
    {
        $this->setupTransports();

        $this->loadRoutes();
        $subject = new \stdClass();
        $to = 'name@domain.com';
        $user = new User(['email' => $to]);
        $event = new Event('resetPasswordNotification', $subject,['User' => $user, 'new' => false]);
        $this->assertInstanceOf(Event::class, $event);
        $listener = new NotificationListeners();

        $listener->resetPasswordNotification($event);
//        $msg = TestEmailTransport::getMessages();
//        debug($msg);

        $this->assertMailSentTo($to);
        $this->assertMailSubjectContains(EmailCon::RESET_PASSWORD_EMAIL_TITLE);
        $this->assertMailContains('Password reset for Rods and Cones');

        $this->cleanupEmailTrait();
    }

    public function test_inventoryCompleteNotification()
    {
        /* @var CustomerInventoryStatusReporter $customerInventoryStatusReporter */
        $customerInventoryStatusReporter = $this->loadFixtureScenario(
            CustomerInventoryStatusReporterScenario::class,
            variant: CISRCon::COMPLETE
        );

        debug($customerInventoryStatusReporter);

//        debug($customerInventoryStatusReporter->customer());

        debug($customerInventoryStatusReporter->getUserEmails());

//        $this->setupTransports();
//
//        $this->loadRoutes();

    }

}
