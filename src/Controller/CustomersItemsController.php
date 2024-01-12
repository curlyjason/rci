<?php
declare(strict_types=1);

namespace App\Controller;

use App\Application;
use App\Forms\OrderNowForm;
use App\Model\Entity\Order;
use App\Utilities\CustomerFocus;
use Cake\Http\Response;

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
        /**
         * uploading can only be done for one customer at a time
         */
        if (!(Application::container()->get(CustomerFocus::class))->focus($this)) {
            return $this->render('/Admin/Items/customer_focus');
        }

        $this->setUserCustomerVariable();
        $query = $this->CustomersItems->find()
            ->where(['customer_id' => $this->readSession('Auth')->customer_id])
            ->contain(['Customers', 'Items']);
        $customersItems = $this->paginate($query);

        $this->set(compact('customersItems'));
    }

    public function setTriggerLevels()
    {
        /**
         * uploading can only be done for one customer at a time
         */
        if (!(Application::container()->get(CustomerFocus::class))->focus($this)) {
            return $this->render('/Admin/Items/customer_focus');
        }

        $result = $this->createItemListAndFilterMap();
        extract($result); //customerItems, masterFilterMap, items

        $this->set(compact('masterFilterMap', 'items', 'customersItems'));
    }

    public function orderNow()
    {
        /**
         * Insure we have customer focus
         */
        if (!(Application::container()->get(CustomerFocus::class))->focus($this)) {
            return $this->render('/Admin/Items/customer_focus');
        }
        /**
         * Process a post if we have one
         */
        $Form = Application::container()->get(OrderNowForm::class);
        if ($Form->execute($this->request->getData())) {
            /**
             * Either leave after successful save or go to fix-errors UI
             */
            return $this->render($this->saveNewOrder($Form));
        }
        /**
         * Render the Order Now form
         */
        $result = $this->createItemListAndFilterMap();
        extract($result); //customerItems, masterFilterMap, items
        $this->set(compact('masterFilterMap', 'items', 'customersItems'));

        return $this->render();
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
     * @return mixed
     */
    private function createItemListAndFilterMap(): mixed
    {
        $accum = [
            'masterFilterMap' => new \stdClass(),
            'items' => [],
            'customersItems' => $this->GetPaginatedItemsForUser()
        ];

        return collection($accum['customersItems'])
            ->reduce(function ($accum, $customerItem) {
                $id = $customerItem->id;
                $accum['masterFilterMap']->$id = $customerItem->item->name;
                $accum['items'][$id] = $customerItem->item->name;

                return $accum;
            }, $accum);
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
        return $this->paginate($query);
    }

    private function saveNewOrder(OrderNowForm $executedForm): mixed
    {
        $order = $executedForm->getData('result');
        /* @var Order $order */

        if (!$order->hasErrors() && $this->fetchTable('Orders')->save($order)) {
            $this->Flash->success('The order has been saved');

            return '/Pages/home';
        }
        $this->Flash->error('The order has not been saved');
        $this->set(compact('executedForm'));

        return 'Admin/Orders/resolveErrors';
    }
}
