<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController
{

    public const BULK_IMPORT_ROOT = WWW_ROOT . 'bulk-import/';
    public const BULK_ARCHIVE_ROOT = self::BULK_IMPORT_ROOT . 'archive/';
    protected string $importFilePath = self::BULK_IMPORT_ROOT . 'import.txt';
    protected string $errorFilePath = self::BULK_IMPORT_ROOT . 'errors.txt';

    private function importArchivePath(): string
    {
        return self::BULK_ARCHIVE_ROOT . time();
    }

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
    public function view($id = null)
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
    public function edit($id = null)
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
    public function delete($id = null)
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

    public function import()
    {
        $file = new \SplFileInfo($this->importFilePath);
        /**
         * if there is no uploaded file and upload() is not
         * successfully called (creates an upload file) during
         * a valid POST event, render the upload form
         */
        if (!$file->isFile() && !$this->upload()) {
            $this->render('upload');
        }

        //only reach here when an uploaded file exists
        $this->_processUploadFile();

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
        $file = new \SplFileInfo($this->importFilePath);
        /**
         * if there is no uploaded file and upload() is not
         * successfully called (creates an upload file) during
         * a valid POST event, render the upload form
         */
        if (!$file->isFile() && !$this->upload()) {
            $this->render('upload');
        }

        //only reach here when an uploaded file exists
        $import = fopen($this->importFilePath, 'r+');
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
    protected function upload(): bool
    {
        if ($this->request->is('post')) {
            $upload = $this->request->getData('upload');
             /* @var \Laminas\Diactoros\UploadedFile $upload */

            if (!in_array($upload->getClientMediaType(), ['text/plain', 'text/csv'])) {
                $this->Flash->error('Only .txt or .csv files are allowed');
            }
            if ($upload->getSize() > 1024 * 1024 / 2) {
                $this->Flash->error('Files over 1mb are not allowed');
            }
            if (empty($this->request->getSession()->read('Flash'))) {
                $upload->moveTo($this->importFilePath);
                return true;
            }
        }
        return false;
    }

    private function _processUploadFile()
    {
        //What if the file content is deemed invalid?
        try {
            $import = fopen($this->importFilePath, 'r');
            $errors = fopen($this->errorFilePath, 'r+');
            $archive = fopen($this->importArchivePath(), 'w');

            //read in the headers
            $headers = fgetcsv($import);

        } catch (\Exception $e) {
            $import = null;
            $errors = null;
            $archive = null;
            throw $e;
        }


        //  Flash message try again with detail
        //walk through each line
        //save it if it is new
        //what if it is an edit?
        //
        //collect in a new file if it is not valid
    }
}
