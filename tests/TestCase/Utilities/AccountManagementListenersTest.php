<?php

namespace App\Test\TestCase\Utilities;

use App\Constants\EmailCon;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utilities\AccountManagementListeners;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\MailerAwareTrait;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;

class AccountManagementListenersTest extends TestCase
{

    use EmailTrait;

    /**
     * @param $event Event
     */
    public function xtest_newAccountNotification($event)
    {
    }

    public function test_resetPasswordNotification_forgot()
    {
        $this->loadRoutes();
        $subject = new \stdClass();
        $to = 'name@domain.com';
        $user = new User(['email' => $to]);
        $event = new Event('resetPasswordNotification', $subject,['User' => $user, 'new' => false]);
        $this->assertInstanceOf(Event::class, $event);
        $listener = new AccountManagementListeners();

        $listener->resetPasswordNotification($event);

        $this->assertMailSentTo($to);
        $this->assertMailSubjectContains(EmailCon::RESET_PASSWORD_EMAIL_TITLE);
        $this->assertMailContains('Password reset for Rods and Cones');
    }

}
