<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * CustomerItems Controller
 *
 */
class CustomerItemsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->CustomerItems->find();
        $customerItems = $this->paginate($query);

        $this->set(compact('customerItems'));
    }

    /**
     * View method
     *
     * @param string|null $id Customer Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customerItem = $this->CustomerItems->get($id, contain: []);
        $this->set(compact('customerItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customerItem = $this->CustomerItems->newEmptyEntity();
        if ($this->request->is('post')) {
            $customerItem = $this->CustomerItems->patchEntity($customerItem, $this->request->getData());
            if ($this->CustomerItems->save($customerItem)) {
                $this->Flash->success(__('The customer item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer item could not be saved. Please, try again.'));
        }
        $this->set(compact('customerItem'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerItem = $this->CustomerItems->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerItem = $this->CustomerItems->patchEntity($customerItem, $this->request->getData());
            if ($this->CustomerItems->save($customerItem)) {
                $this->Flash->success(__('The customer item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer item could not be saved. Please, try again.'));
        }
        $this->set(compact('customerItem'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer Item id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerItem = $this->CustomerItems->get($id);
        if ($this->CustomerItems->delete($customerItem)) {
            $this->Flash->success(__('The customer item has been deleted.'));
        } else {
            $this->Flash->error(__('The customer item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
