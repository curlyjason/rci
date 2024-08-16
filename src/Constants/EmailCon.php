<?php


namespace App\Constants;


use App\Model\Table\UsersTable;
use Cake\ORM\Locator\LocatorAwareTrait;

class EmailCon
{

    use LocatorAwareTrait;

    const REGISTRATION_EMAIL_TITLE = 'Rods and Cones User Registration Instructions';

    const RESET_PASSWORD_EMAIL_TITLE = 'Rods and Cones Reset Password Instructions';
    const INVENTORY_DUE = 'It\'s time to do your inventory';
    const INVENTORY_DONE = 'Thank you for completing your inventory';
    const NEW_ORDER = 'Thank you for completing your inventory';
    const ADMINS = [
        'ddrake@dreamingmind.com',
        'jason@curlymedia.com'
    ];
    const ORDER_EMAILS = [
        'rci_orders@rodsandcones.com',
        'ddrake@dreamingmind.com'
    ];

    public static function getCustomerEmails(int $customer_id)
    {
        return (new EmailCon)
            ->fetchTable('Users')
            ->getCustomerUsersEmails($customer_id);
    }

    public static function isAdmin($email)
    {
        return in_array($email, EmailCon::ADMINS);
    }

}
