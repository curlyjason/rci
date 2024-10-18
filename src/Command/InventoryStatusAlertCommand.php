<?php

namespace App\Command;

use App\Model\Entity\CustomersItem;
use App\Model\Table\CustomersTable;
use App\Utilities\NotificationListeners;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\EventTrigger;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use function debug;

class InventoryStatusAlertCommand extends Command
{
    use EventTrigger;

    private CustomersTable|Table $Customers;

    public function __construct()
    {
        $this->Customers = $this->fetchTable('Customers');
        EventManager::instance()->on(new NotificationListeners());
    }

    public function execute(Arguments $args, ConsoleIo $io): void
    {
        parent::execute($args, $io);

        collection($this->Customers->find()->contain(['Users'])->all())
            ->map(function ($customer) {
                $statusReport = new CustomerInventoryStatusReporter($customer);
                $statusReport->chooseNotification();
            })
            ->toArray();
    }

}
