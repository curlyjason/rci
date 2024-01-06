<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CustomersItems Controller
 *
 * @property \App\Model\Table\CustomersItemsTable $CustomersItems
 */
class CustomersItemsController extends AppController
{

    public ?string $defaultTable = 'items';

    public function takeInventory()
    {
        $customersItems = $this->GetPaginatedItemsForUser();

        $this->set(compact('customersItems'));
    }

    public function setTriggerLevels()
    {
        $customersItems = $this->GetPaginatedItemsForUser();
        $result = $this->createItemListAndFilterMap($customersItems);
        extract($result); //masterFilterMap, items

        $this->set(compact('masterFilterMap', 'items', 'customersItems'));
    }

    public function orderNow()
    {
        $result = [];
        if ($this->request->is('post')) {
            osd($this->request->getData(), 'posted data');
            foreach ($this->request->getData('live') as $index => $islive) {
                if ($islive != 'false') {
                    $result[] = [
                        'order_quantity' => $this->request->getData('order_quantity')[$index],
                        'id' => $this->request->getData('item_id')[$index],
                    ];
                }
            }
            osdd($result, 'processed data');
        }
        $customersItems = $this->GetPaginatedItemsForUser();
        $result = $this->createItemListAndFilterMap($customersItems);
        extract($result); //masterFilterMap, items

        $this->set(compact('masterFilterMap', 'items', 'customersItems'));
    }

    /**
     * @return mixed
     */
    private function setUserCustomerVariable(): void
    {
        $user = $this->fetchTable('Users')
            ->find()
            ->where(['Users.id' => $this->readSession('Auth')->id])
            ->contain(['Customers'])
            ->first();

        $this->set(compact('user'));
    }

    /**
     * @param \Cake\Datasource\Paging\PaginatedInterface $customersItems
     * @return mixed
     */
    private function createItemListAndFilterMap(\Cake\Datasource\Paging\PaginatedInterface $customersItems): mixed
    {
        $result = collection($customersItems)
            ->reduce(function ($accum, $customerItem) {
                $id = $customerItem->id;
                $accum['masterFilterMap']->$id = $customerItem->item->name/*
                    . ' ' . $customerItem->item->description
                    . ' ' . $customerItem->item->vendors[0]->_joinData->sku*/
                ;
                $accum['items'][$id] = $customerItem->item->name;

                return $accum;
            }, ['masterFilterMap' => new \stdClass(), 'items' => []]);
        return $result;
    }

    /**
     * @return \Cake\Datasource\Paging\PaginatedInterface
     */
    private function GetPaginatedItemsForUser(): \Cake\Datasource\Paging\PaginatedInterface
    {
        $this->setUserCustomerVariable();
        $query = $this->Items->find()
            ->where(['customer_id' => $this->readSession('Auth')->customer_id])
            ->contain(['Customers']);
        $customersItems = $this->paginate($query);
        return $customersItems;
    }
}
