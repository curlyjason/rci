<?php

namespace App\Command;

use App\Model\Entity\CustomersItem;
use App\Utilities\AccountManagementListeners;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\EventTrigger;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query\SelectQuery;
use function debug;

class InventoryStatusAlertCommand extends Command
{
    use EventTrigger;

    private \Cake\ORM\Table $Customers;

    public function __construct()
    {
        $this->Customers = $this->fetchTable('Customers');
        EventManager::instance()->on(new AccountManagementListeners());
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        parent::execute($args, $io);

        $customerInventoryStatusReporters = collection($this->Customers->find()->all())
            ->map(function ($customer) {
                return new CustomerInventoryStatusReporter($customer);
            });

        collection($customerInventoryStatusReporters)
            ->map(function ($statusReport, $index) {
                if ($statusReport->inventoryComplete()) {
                    $this->trigger('inventoryComplete', ['statusReporter' => $statusReport]);
                }
                else {
                    $this->trigger('inventoryDue', ['statusReporter' => $statusReport]);
                }
                debug($index);
            })
            ->toArray();
    }

}
