<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * CustomersItems Controller
 *
 * @property \App\Model\Table\CustomersItemsTable $CustomersItems
 */
class CustomersItemsController extends AppController
{
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
    public function view(?string $id = null)
    {
        $customersItem = $this->CustomersItems->get($id, contain: ['Customers', 'Items']);
        $this->set(compact('customersItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(bool $redir = true)
    {
        $customersItem = $this->CustomersItems->newEmptyEntity();
        if ($this->request->is('post')) {
            $customersItem = $this->CustomersItems->patchEntity($customersItem, $this->request->getData());
            if ($this->CustomersItems->save($customersItem)) {
                $this->Flash->success(__('The customers item has been saved.'));

                if ($redir) {
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__('The customers item could not be saved. Please, try again.'));
        }
        $customers = $this->CustomersItems->Customers->find('list', limit: 200)->all();
        $items = $this->CustomersItems->Items->find('list', limit: 200)->all();
        $this->set(compact('customersItem', 'customers', 'items', 'redir'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customers Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
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
    public function delete(?string $id = null, bool $redir = true)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customersItem = $this->CustomersItems->get($id);
        if ($this->CustomersItems->delete($customersItem)) {
            $this->Flash->success(__('The customers item has been deleted.'));
        } else {
            $this->Flash->error(__('The customers item could not be deleted. Please, try again.'));
        }

        if ($redir) {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * @param $id
     * @param $custId
     * @return \Cake\Http\Response|null
     */
    public function customerDelete($id, $custId)
    {
        $this->delete($id, false);

        return $this->redirect(
            [
                'controller' => 'customers',
                'action' => 'view',
                $custId,
            ]
        );
    }

    public function customerAdd($custId)
    {
        $customer = $this->CustomersItems->Customers->get($custId);
        $this->set(compact('customer'));
        
        return $this->redirect(
            [
                'controller' => 'customers',
                'action' => 'view',
                $custId,
            ]
        );

    }
}
