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
        ];
    }

    /**
     * @param $event Event
     */
    public function newAccountNotification($event)
    {
        $message = "The email sent to {$event->getData()['email']} provides instructions to complete your registration."
            . ' Look for the subject line ' . EmailCon::REGISTRATION_EMAIL_TITLE;

        $this->Mailer
            ->addTo($event->getData()['email'])
//            ->addTo('jason@curlymedia.com')
//            ->addTo('ddrake@dreamingmind.com')
            ->setSubject(EmailCon::REGISTRATION_EMAIL_TITLE)
            ->deliver($message);

        $event->getSubject()->Flash->success($message);
    }

    public function resetPasswordNotification($event)
    {
        $data = $event->getData();
        if($data['new']){
            $template = 'new_user_password';
            $subject = EmailCon::REGISTRATION_EMAIL_TITLE;
        }
        else {
            $template = 'reset_password';
            $subject = EmailCon::RESET_PASSWORD_EMAIL_TITLE;
        }
        /**
         * @todo fix the recipient email
         */
        $this->Mailer
            ->setEmailFormat('html')
            ->addTo($event->getData()['User']->email)
            ->addTo('jason@curlymedia.com')
//            ->addTo('ddrake@dreamingmind.com')
            ->setSubject($subject)
            ->setViewVars(['User' => $data['User']])
            ->viewBuilder()
            ->setTemplate($template);

        $this->Mailer->send();
    }

}