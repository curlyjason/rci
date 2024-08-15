<?php


namespace App\Utilities;


use App\Constants\EmailCon;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Mailer;

class AccountManagementListeners implements EventListenerInterface
{

    /**
     * @var Mailer
     */
    private $Mailer;

    public function __construct()
    {
        $this->Mailer = ProvideMailer::instance();
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'newAccountNotification' => 'newAccountNotification',
            'resetPasswordNotification' => 'resetPasswordNotification',
            'inventoryComplete' => 'inventoryComplete',
            'inventoryDue' => 'inventoryDue',
        ];
    }

    /**
     * @param $event Event
     */
    public function newAccountNotification($event)
    {
        $message = "The email sent to {$event->getData('User')->email} provides instructions to complete your registration."
            . ' Look for the subject line ' . EmailCon::REGISTRATION_EMAIL_TITLE;

        $this->Mailer
            ->addTo($event->getData('User')->email)
            ->addBcc('jason@curlymedia.com')
            ->addBcc('ddrake@dreamingmind.com')
            ->setSubject(EmailCon::REGISTRATION_EMAIL_TITLE)
            ->deliver($message);

        $event->getSubject()->Flash->success($message);
    }

    public function resetPasswordNotification($event)
    {
        $data = $event->getData();
        /**
         * @todo make a `match` statement here
         */
        if($data['new']){
            $template = 'new_user_password';
            $subject = EmailCon::REGISTRATION_EMAIL_TITLE;
        }
        else {
            $template = 'reset_password';
            $subject = EmailCon::RESET_PASSWORD_EMAIL_TITLE;
        }
        /**
         * @todo abstract the recipient email bcc's so there is a central repository of addresses
         */
        $this->Mailer
            ->setEmailFormat('html')
            ->addTo($event->getData()['User']->email)
            ->addBcc('jason@curlymedia.com')
            ->addBcc('ddrake@dreamingmind.com')
            ->setSubject($subject)
            ->setViewVars(['User' => $data['User']])
            ->viewBuilder()
            ->setTemplate($template);

        $this->Mailer->send();
    }

    public function inventoryComplete(Event $event)
    {
        //set the flag value to show 'complete' was processed this month
        $this->Mailer
            ->setEmailFormat('html')
            ->addTo('ddrake@dreamingmind.com')
            ->setSubject(EmailCon::INVENTORY_DONE)
            ->setViewVars(['statusReporter' => $event->getData('statusReporter')])
            ->viewBuilder()
            ->setTemplate('inventory_complete_customer');
        $this->Mailer->send();
    }

    public function inventoryDue(Event $event)
    {
        $this->Mailer
            ->setEmailFormat('html')
            ->addTo('ddrake@dreamingmind.com')
            ->setSubject(EmailCon::INVENTORY_DUE)
            ->setViewVars(['statusReporter' => $event->getData('statusReporter')])
            ->viewBuilder()
            ->setTemplate('inventory_due');
        $this->Mailer->send();
    }

}
