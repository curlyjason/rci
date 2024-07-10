<?php

namespace App\Test\TestCase\Utilities;

use App\Constants\EmailCon;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utilities\AccountManagementListeners;
use Cake\Event\Event;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\TestEmailTransport;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class AccountManagementListenersTestx extends TestCase
{

    use TruncateDirtyTables;
    use EmailTrait;

    /**
     * @param $event Event
     */
    public function test_newAccountNotification($event)
    {
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
        $listener = new AccountManagementListeners();

        $listener->resetPasswordNotification($event);
//        $msg = TestEmailTransport::getMessages();

        $this->assertMailSentTo($to);
        $this->assertMailSubjectContains(EmailCon::RESET_PASSWORD_EMAIL_TITLE);
        $this->assertMailContains('Password reset for Rods and Cones');

        $this->cleanupEmailTrait();
    }

}
