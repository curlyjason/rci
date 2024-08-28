<?php


namespace App\Utilities;


use App\Constants\EmailCon;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Mailer;

class NotificationListeners implements EventListenerInterface
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
        $userEmail = $event->getData('User')->email;
        $message = "The email sent to {$userEmail} provides instructions to complete your registration."
            . ' Look for the subject line ' . EmailCon::REGISTRATION_EMAIL_TITLE;

        $this->Mailer
            ->addTo($userEmail)
            ->setSubject(EmailCon::REGISTRATION_EMAIL_TITLE);

        $this->addBccs(EmailCon::ADMINS);
        $this->Mailer->deliver($message);

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

        $this->Mailer
            ->setEmailFormat('html')
            ->addTo($event->getData()['User']->email)
            ->setSubject($subject)
            ->setViewVars(['User' => $data['User']])
            ->viewBuilder()
            ->setTemplate($template);

        $this->addBccs(EmailCon::ADMINS);
        $this->Mailer->send();
    }

    public function inventoryComplete(Event $event)
    {
        /* @todo set the flag value to show 'complete' was processed this month */
        $this->inventoryStatusEmailSender(
            $event->getData('statusReporter'),
            'inventory_complete_customer'
        );
    }

    public function orderToBePlaced(Event $event)
    {
        /* @todo set the flag value to show 'order_placed' was processed this month */
        $this->inventoryStatusEmailSender(
            $event->getData('statusReporter'),
            'order_placed'
        );
    }

    private function inventoryStatusEmailSender(CustomerInventoryStatusReporter $statusReporter, $template)
    {
        $this->Mailer
            ->setEmailFormat('html')
            ->setSubject(EmailCon::NEW_ORDER)
            ->setViewVars(['statusReporter' => $statusReporter])
            ->viewBuilder()
            ->setTemplate($template);

        $this->addTos($statusReporter->getUserEmails());
        $this->addBccs(EmailCon::ORDER_EMAILS);
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

    private function addBccs(array $emails) : void
    {
        foreach ($emails as $email){
            $this->Mailer->addBcc($email);
        }
    }

    private function addTos(array $emails) : void
    {
        foreach ($emails as $email){
            $this->Mailer->addTo($email);
        }
    }

}
