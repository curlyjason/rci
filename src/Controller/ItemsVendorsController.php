<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ItemsVendors Controller
 *
 * @property \App\Model\Table\ItemsVendorsTable $ItemsVendors
 */
class ItemsVendorsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->ItemsVendors->find()
            ->contain(['Items', 'Vendors']);
        $itemsVendors = $this->paginate($query);

        $this->set(compact('itemsVendors'));
    }

    /**
     * View method
     *
     * @param string|null $id Items Vendor id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $itemsVendor = $this->ItemsVendors->get($id, contain: ['Items', 'Vendors']);
        $this->set(compact('itemsVendor'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $itemsVendor = $this->ItemsVendors->newEmptyEntity();
        if ($this->request->is('post')) {
            $itemsVendor = $this->ItemsVendors->patchEntity($itemsVendor, $this->request->getData());
            if ($this->ItemsVendors->save($itemsVendor)) {
                $this->Flash->success(__('The items vendor has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The items vendor could not be saved. Please, try again.'));
        }
        $items = $this->ItemsVendors->Items->find('list', limit: 200)->all();
        $vendors = $this->ItemsVendors->Vendors->find('list', limit: 200)->all();
        $this->set(compact('itemsVendor', 'items', 'vendors'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Items Vendor id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $itemsVendor = $this->ItemsVendors->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $itemsVendor = $this->ItemsVendors->patchEntity($itemsVendor, $this->request->getData());
            if ($this->ItemsVendors->save($itemsVendor)) {
                $this->Flash->success(__('The items vendor has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The items vendor could not be saved. Please, try again.'));
        }
        $items = $this->ItemsVendors->Items->find('list', limit: 200)->all();
        $vendors = $this->ItemsVendors->Vendors->find('list', limit: 200)->all();
        $this->set(compact('itemsVendor', 'items', 'vendors'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Items Vendor id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $itemsVendor = $this->ItemsVendors->get($id);
        if ($this->ItemsVendors->delete($itemsVendor)) {
            $this->Flash->success(__('The items vendor has been deleted.'));
        } else {
            $this->Flash->error(__('The items vendor could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
