<?php

namespace App\Test\TestCase\Utilities;

use App\Constants\CISRCon;
use App\Constants\EmailCon;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\Scenario\CustomerInventoryStatusReporterScenario;
use App\Test\Scenario\IntegrationDataScenario;
use App\Test\Traits\DebugTrait;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\NotificationListeners;
use Cake\Event\Event;
use Cake\Mailer\Message;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\TestEmailTransport;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class NotificationListenersTest extends TestCase
{

    use TruncateDirtyTables;
    use EmailTrait;
    use ScenarioAwareTrait;
    use DebugTrait;

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

//        $this->writeEmails(TestEmailTransport::getMessages());

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
        $to = $customerInventoryStatusReporter->getUserEmails();

        $this->setupTransports();

        $this->loadRoutes();
        $subject = new \stdClass();

        $event = new Event('inventoryComplete', $subject, ['statusReporter' => $customerInventoryStatusReporter]);
        $this->assertInstanceOf(Event::class, $event);
        $listener = new NotificationListeners();

        $listener->inventoryComplete($event);

//        debug($to);
        $msg = TestEmailTransport::getMessages()[0];
//        debug($msg->getTo());
//        debug($msg->getBcc());

        $this->writeEmails(TestEmailTransport::getMessages());

//        $this->assertMailSentTo($to);
//        $this->assertMailSubjectContains(EmailCon::RESET_PASSWORD_EMAIL_TITLE);
//        $this->assertMailContains('Password reset for Rods and Cones');

        $this->cleanupEmailTrait();

    }

}
