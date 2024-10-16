<?php

namespace App\Utilities;

use App\Model\Entity\Customer;
use App\Model\Entity\CustomersItem;
use Cake\ORM\Locator\LocatorAwareTrait;

class CustomerInventoryStatusReporter
{
    use LocatorAwareTrait;
    use DateUtilityTrait;

    protected Customer $_customer;
    protected array $_completeItems = [];
    protected array $_incompleteItems = [];
    protected $_newOrderPost = [
        'order_now' => false,
        'name' => '',
        'email' => '',
        'order_quantity' => [],
        'id' => [],
    ];

    /**
     * <pre>
     * [
     *   'key' => [ //regex pattern
     *     'interval' node (callable),
     *     'result' node
     *   ]
     * ]
     *
     * 'key' is compared to customers->last_notification.
     * 'interval' runs against customers->last_inventory_notification (datetime).
     * 'result' sets customers->last_notification (if rule is satisfied).
     * </pre>
     *
     * @var array
     */
    protected $ruleWhenNotComplete =             [
        '^$' => [
            'lastNoticeDateTrigger' => 'duringLastCycle',
            'nextNotice' => 'firstPrompt',
        ],
        'firstPrompt' => [
            'lastNoticeDateTrigger' => 'atLeastADayOld',
            'nextNotice' => 'secondPrompt',
        ],
        'secondPrompt' => [
            'lastNoticeDateTrigger' => 'atLeastADayOld',
            'nextNotice' => 'autoReOrerPrompt',
        ],
        'autoReOrderPrompt' => [
            'lastNoticeDateTrigger' => 'atLeastADayOld',
            'nextNotice' => 'confirmThisOrderPrompt', //adjust counts, set inventory to complete
        ],
    ];

    /**
     * <pre>
     * [
     *   'key' => [ //regex pattern
     *     'interval' node (callable),
     *     'result' node
     *   ]
     * ]
     *
     * 'key' is compared to customers->last_notification.
     * 'interval' runs against customers->last_inventory_notification (datetime).
     * 'result' sets customers->last_notification (if rule is satisfied).
     * </pre>
     *
     * @var array
     */
    protected $ruleWhenComplete = [
        /**
         * Customer takes inventory before the system can prompt them.
         * firstDayOfCycle test insures we don't order again later in the month
         */
        '^$' => [
            'lastNoticeDateTrigger' => 'firstDayOfCycle',
            'nextNotice' => 'confirmThisOrder',
        ],
        /**
         * Somehow, inventory was done after any one of the 'Prompts' were sent
         */
        '(?i)prompt$' => [
            'lastNoticeDateTrigger' => 'atLeastADayOld',
            'nextNotice' => 'confirmThisOrder',
        ],
        /**
         * We gave the customer one day to intervene. Make this order!
         */
        'confirmThisOrder' => [
            'lastNoticeDateTrigger' => 'atLeastADayOld',
            'nextNotice' => '',//make order, send Stephanie (and client?) the order data
        ],
    ];

    public function __construct(Customer $customer)
    {
        $this->_customer = $customer;
        $customerItems = $this->fetchTable('CustomersItems')
            ->findInventoryForCustomer($customer->id);
        foreach ($customerItems as $customerItem) {
            $this->insert($customerItem);
        }
    }

    protected function lastNotificationDate()
    {
        return $this->_customer->last_inventory_notification;
    }

    protected function insert(CustomersItem $item):void
    {
        if ($item->hasBeenInventoried()) {
            $this->_completeItems[] = $item;
        }
        else {
            $this->_incompleteItems[] = $item;
        }
    }

    public function inventoryComplete(): bool
    {
        return empty($this->_incompleteItems);
    }

    public function customer(): Customer
    {
        return $this->_customer;
    }

    public function getItems($status)
    {
        return $this->$status;
    }

    public function getUserEmails(): array {
        return collection($this->customer()->users)
            ->extract(function($user) {
                return $user->email;
            })
            ->toArray();
    }

    /**
     * Make data required by OrderNowForm
     *
     * @return array
     */
    public function getNewOrderPost($user)
    {
        $this->_newOrderPost['email'] = $user->email;
        if ($this->inventoryComplete()) {
            $this->_newOrderPost['order_now'] = true;
            /* @var CustomersItem $completeItem */
            foreach ($this->_completeItems as $index => $completeItem) {
                $this->_newOrderPost['order_quantity'][] = $completeItem->order_amount;
                $this->_newOrderPost['id'][] = $completeItem->id;
            }
        }
        return $this->_newOrderPost;
    }

    public function __debugInfo(): ?array
    {
        return [
            '_customer (entity)' => $this->_customer->name,
            'inventoryComplete' => $this->inventoryComplete(),
            '_complete (entity)' => $this->shortenPropertyForDebug($this->_completeItems, 'name'),
            '_incomplete (entity)' => $this->shortenPropertyForDebug($this->_incompleteItems, 'name'),
        ];

    }

    private function shortenPropertyForDebug($data, $field): array
    {
        $ar = [];
        foreach ($data as $datum) {
            $ar[] = $datum->$field;
        }
        return $ar;
    }

    /**
     * =================================================================================================
     */

    protected function lastNoticeWas(string $notice): bool {

        return preg_match("/$notice/", $this->customer()->last_notice)===1;
//        return true;
    }

    protected function readyForNoticeAfter(string $notice, $callable, $next) {
        if ($this->lastNoticeWas($notice) && $callable()) {
            $result = $next;
        }
        return $result ?? null;
    }

    protected function nextNoticeAfter(string $notice): string {
        return 'eventName';
    }



    public function chooseNotification()
    {
        if($this->inventoryComplete()) {
            return $this->enactRules(new \ArrayIterator($this->ruleWhenComplete));
        }
        else {
            return $this->enactRules(new \ArrayIterator($this->ruleWhenNotComplete));
        }
    }

    public function enactRules(\ArrayIterator $ruleSet)
    {
        while ($ruleSet->valid()) {
            extract($ruleSet->current()); //lastNoticeDateTrigger, nextNotice
            if ($this->lastNoticeWas( notice: $ruleSet->key()) && $lastNoticeDateTrigger($this->lastNotificationDate())) {
                return $nextNotice;
            }
            $ruleSet->next();
        }
        return false;
    }

}
