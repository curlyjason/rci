<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constants\Fixture;
use App\Model\Entity\Item;
use App\Utilities\CustomerFocus;
use App\Utilities\ImportItems;
use SplFileInfo;
/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AdminController
{
        //<editor-fold desc="BAKED METHODS">
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Items->find();
        $items = $this->paginate($query);

        $this->set(compact('items'));
    }

    /**
     * View method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $item = $this->Items->get($id, contain: ['Customers']);
        $this->set(compact('item'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $item = $this->Items->newEmptyEntity();
        if ($this->request->is('post')) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        $customers = $this->Items->Customers->find('list', limit: 200)->all();
        $this->set(compact('item', 'customers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $item = $this->Items->get($id, contain: ['Customers']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        $customers = $this->Items->Customers->find('list', limit: 200)->all();
        $this->set(compact('item', 'customers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $item = $this->Items->get($id);
        if ($this->Items->delete($item)) {
            $this->Flash->success(__('The item has been deleted.'));
        } else {
            $this->Flash->error(__('The item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    //</editor-fold>

    public function import()
    {
        /**
         * uploading can only be done for one customer at a time
         */
        if (!(new CustomerFocus())->focus($this)) {
            return $this->render('customerFocus');
        }
        /**
         * if there is no uploaded file and upload() is not
         * successfully called (creates an upload file) during
         * a valid POST event, render the upload form
         */
        if (!$this->uploadExists()) {
            return $this->render('upload');
        }
        $importer = new ImportItems();
        $importer->processUploadFile();
        /**
         * _processUploadFile set $this->import object
         * keys:
         *      source          resource
         *      error           resource
         *      archive         resource
         *      errorCount      int
         *      archiveCount    int
         *      archivePath     string
         */
        if ((bool) $importer->archiveCount) {
            $this->Flash->success(
                "Total items imported for {$importer->customer}: {$importer->archiveCount}"
            );
            $this->Flash->success("Import data archive at: {$importer->archivePath}");
        }
        if ((bool) $importer->errorCount) {
            $this->Flash->success("Total lines with errors: {$importer->errorCount}");
        }
        foreach($importer->flashError as $message) {
            $this->Flash->error($message);
        }
        foreach($importer->flashSuccess as $message) {
            $this->Flash->success($message);
        }

        //render a report page
        //show archived lines
        //show errors

        return $this->render();
    }

    /**
     * Dev method for the reusable bulk import system
     *
     * See `import()` for the minimally viable version
     *
     * @return void
     */
    public function bulkImport()
    {
        $file = new SplFileInfo(ImportItems::IMPORT_PATH);
        /**
         * if there is no uploaded file and upload() is not
         * successfully called (creates an upload file) during
         * a valid POST event, render the upload form
         */
        if (!$file->isFile() && !$this->uploadExists()) {
            $this->render('upload');
        }

        //only reach here when an uploaded file exists
        $import = fopen(ImportItems::IMPORT_PATH, 'r+');
        $post = $this->request->getData();
        $schema = $this->Items->getSchema();
        $inputCols = [
            'Product/Service',
            'Type',
            'Description',
            'Price',
            'Cost',
            'Qty On Hand',
        ];

        $this->set(compact('schema', 'inputCols', 'post', 'import'));
    }

    /**
     * If no upload is found, get or accept one
     *
     * bulkImport() calls here to verify/establish the existence of a file to process.
     * - If none is detected, this method will render a form to get one.
     * - If we are in post mode with a file in hand, this method will
     * save it and return to bulkImport.
     * - If a file is in place, we'll just return to bulkImport if processing
     *
     * @return void
     */
    protected function uploadExists(): bool
    {
        /**
         * Code might move and rename an error file for retry
         */
        if ((new SplFileInfo(ImportItems::IMPORT_PATH))->isFile()) {
            return true;
        }

        if ($this->request->is('post') && $this->request->getData('upload')) {
            $upload = $this->request->getData('upload');
            /** @var \Laminas\Diactoros\UploadedFile $upload */

            if (!in_array($upload->getClientMediaType(), ['text/plain', 'text/csv'])) {
                $this->Flash->error('Only .txt or .csv files are allowed');
            }
            if ($upload->getSize() > 1024 * 1024 / 2) {
                $this->Flash->error('Files over 1mb are not allowed');
            }
            if (empty($this->request->getSession()->read('Flash'))) {
                $upload->moveTo(ImportItems::IMPORT_PATH);

                return true;
            }
        }

        return false;
    }

    private function customerFocus()
    {
        if ($this->Authentication->isImpersonating()) {
            return true;
        }

        if ($this->request->is('post')) {
            $user = $this->fetchTable('Users')->get($this->request->getData('id'));
            $this->Authentication->impersonate($user);
            osd($this->Authentication->isImpersonating(), 'is impersonating');
            osd($this->isAdmin(), 'isAdmin');
            osd($this->readSession('Auth'), 'session data');
            osdd($this->request->getData());
            return true;
        }

        $table = $this->fetchTable('Customers');
        $customers = $table
            ->find()
            ->contain(['Users'])
            ->all();
        $this->set(compact('customers', 'table'));

        return false;
    }

    public function testPreparedStatements()
    {
//        osdd($this->getIdentity()->customer_id);

        $t = osdTime();

        // each one is a new Query
        $t->start();
        $customer = $this->Items->Customers->get($this->getIdentity()->customer_id);
        foreach (Fixture::DATA as $it) {
            $item = new Item([]);
            $data = $this->Items->patchEntity($item,[
                Fixture::QBC => $it[0],
                Fixture::N => $it[1],
            ]);

            $this->Items->save($data,['associated' => ['Customers']]);
            $result = $this->Items->Customers->link($data, [$customer]);
        }
        $t->end();
        osd($t->result());

//        $t->start();
//        $data = [];
//        foreach (Fixture::DATA as $it) {
//            $data[] = $this->Items->newEntity([
//                Fixture::QBC => $it[0],
//                Fixture::N => $it[1],
//            ]) ;
//        }
//        $result = $this->Items->saveMany($data) ? 'true ' : 'false ';
//        osd($data);
//        osd($result);
//        $t->end();
//        osd($t->result());


//
//        $t->start(2);
//        $t->start(4);
//
//        // prepare vars for a prepared statement
//        $q = $this->Orders->find()
//            ->where(['Orders.id' => 5])
//            ->contain(['Tenant'/* => ['Items']*/, 'OrderLines']);
//        $c = $this->Orders->getConnection();
//        $p1 = $c->prepare($q);
//
//        $t->end(4);
//
//        // each one is a prepared statement
//        foreach (range(1, 100) as $i) {
//            $p1->bindValue(':c0', 1100 + $i, 'integer');
//            $p1->execute();
//            $r = (new ResultSet($q, $p1))->first();
////            debug($r->id);
//        }
//        $t->end(2);
//        osd($t->result(4));
//        osd($t->result(2));
//
        die;

    }

}
