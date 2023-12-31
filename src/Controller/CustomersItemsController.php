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

    //<editor-fold desc="Baked actions">
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->CustomersItems->find()
            ->contain(['Customers', 'Items']);
        $customersItems = $this->paginate($query);

        $this->set(compact('customersItems'));
    }

    /**
     * View method
     *
     * @param string|null $id Customers Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customersItem = $this->CustomersItems->get($id, contain: ['Customers', 'Items']);
        $this->set(compact('customersItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customersItem = $this->CustomersItems->newEmptyEntity();
        if ($this->request->is('post')) {
            $customersItem = $this->CustomersItems->patchEntity($customersItem, $this->request->getData());
            if ($this->CustomersItems->save($customersItem)) {
                $this->Flash->success(__('The customers item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customers item could not be saved. Please, try again.'));
        }
        $customers = $this->CustomersItems->Customers->find('list', limit: 200)->all();
        $items = $this->CustomersItems->Items->find('list', limit: 200)->all();
        $this->set(compact('customersItem', 'customers', 'items'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customers Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customersItem = $this->CustomersItems->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customersItem = $this->CustomersItems->patchEntity($customersItem, $this->request->getData());
            if ($this->CustomersItems->save($customersItem)) {
                $this->Flash->success(__('The customers item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customers item could not be saved. Please, try again.'));
        }
        $customers = $this->CustomersItems->Customers->find('list', limit: 200)->all();
        $items = $this->CustomersItems->Items->find('list', limit: 200)->all();
        $this->set(compact('customersItem', 'customers', 'items'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Customers Item id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customersItem = $this->CustomersItems->get($id);
        if ($this->CustomersItems->delete($customersItem)) {
            $this->Flash->success(__('The customers item has been deleted.'));
        } else {
            $this->Flash->error(__('The customers item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    //</editor-fold>

    public function takeInventory()
    {
        $this->setUserCustomerVariable();
        $query = $this->CustomersItems->find()
            ->where(['customer_id' => $this->readSession('Auth')->customer_id])
            ->contain(['Customers', 'Items']);
        $customersItems = $this->paginate($query);

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
        $query = $this->CustomersItems->find()
            ->where(['customer_id' => $this->readSession('Auth')->customer_id])
            ->contain(['Customers', 'Items']);
        $customersItems = $this->paginate($query);
        return $customersItems;
    }
}
