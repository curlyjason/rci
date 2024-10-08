<?php

namespace App\Utilities;

use App\Model\Entity\Customer;
use App\Model\Entity\CustomersItem;
use Cake\ORM\Locator\LocatorAwareTrait;

class CustomerInventoryStatusReporter
{
    use LocatorAwareTrait;

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

    public function __construct(Customer $customer)
    {
        $this->_customer = $customer;
        $customerItems = $this->fetchTable('CustomersItems')
            ->findInventoryForCustomer($customer->id);
        foreach ($customerItems as $customerItem) {
            $this->insert($customerItem);
        }
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
}
