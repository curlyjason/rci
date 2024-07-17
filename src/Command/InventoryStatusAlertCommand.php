<?php

namespace App\Command;

use App\Model\Entity\CustomersItem;
use App\Model\Table\CustomersTable;
use App\Utilities\AccountManagementListeners;
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
        EventManager::instance()->on(new AccountManagementListeners());
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        parent::execute($args, $io);

        collection(collection($this->Customers->withIncompleteInventory()))
            ->map(function ($customer) {
                $statusReport = new CustomerInventoryStatusReporter($customer);
                $eventHandler = $statusReport->inventoryComplete()
                    ? 'inventoryComplete'
                    : 'inventoryDue';

                $this->trigger($eventHandler, ['statusReporter' => $statusReport]);
            })
            ->toArray();

    }

}
