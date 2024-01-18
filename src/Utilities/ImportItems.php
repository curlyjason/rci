<?php

namespace App\Utilities;

use App\Application;
use App\Constants\Fixture;
use App\Model\Entity\Customer;
use App\Model\Entity\Item;
use App\Model\Entity\User;
use App\Model\Table\ItemsTable;
use Cake\Datasource\EntityInterface;
use Cake\Http\ServerRequest;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Inflector;
use Exception;

class ImportItems
{
    use LocatorAwareTrait;

    public const BULK_IMPORT_ROOT = WWW_ROOT . 'bulk-import/';
    public const BULK_ARCHIVE_ROOT = self::BULK_IMPORT_ROOT . 'archive/';
    public const IMPORT_PATH = self::BULK_IMPORT_ROOT . 'import.txt';
    public const ERROR_PATH = self::BULK_IMPORT_ROOT . 'errors.txt';
    public const REQUIRED_HEADERS = [
        'name',
        'qb_code',
    ];
    /**
     * @var resource|null
     */
    public $source;
    /**
     * @var resource|null
     */
    public $archive;
    /**
     * @var resource|null
     */
    public $errors;
    public int $archiveCount = 0;
    public null|Customer|EntityInterface $customer;
    public null|string $archivePath;
    public int $errorCount = 0;
    public array $flashError = [];
    public array $flashSuccess = [];
    private ServerRequest $request;
    private User $identity;
    private \Cake\ORM\Table|ItemsTable $Items;

    public function __construct()
    {
        $this->request = Application::container()->get(ServerRequest::class);
        $this->identity = $this->request->getSession()->read('Auth');
        $this->customer = $this->fetchTable('Customers')
            ->get($this->identity->customer_id);
        $this->Items = $this->fetchTable('Items');
    }

    private function importArchivePath(): string
    {
        return self::BULK_ARCHIVE_ROOT . time();
    }

    public function processUploadFile()
    {
        try {
            $this->source = fopen(self::IMPORT_PATH, 'r');
            $this->archivePath = $this->importArchivePath();

            //read in the headers, throws exception
            $headers = $this->checkHeaders($this->source);

            $this->archive = fopen($this->archivePath, 'w');
            $this->errors = fopen(self::ERROR_PATH, 'w');

            while ($newLine = fgetcsv($this->source)) {
                $result = $this->processLine($newLine, $headers);
                if ($result) {
                    fwrite($this->archive, implode(',', $newLine) . "\n");
                } else {
                    fwrite($this->errors, implode(',', $newLine) . "\n");
                }
            }
        } catch (Exception $e) {
            $this->flashError[] = ($e->getMessage());
        }
    }

    private function checkHeaders($import)
    {
        $headers = fgetcsv($import);

        $output = collection($headers)->reduce(function ($accum, $value, $index) {
            $underscore = trim(Inflector::underscore($value));
            if (in_array($underscore, self::REQUIRED_HEADERS)) {
                $accum[$underscore] = $index;
            }

            return $accum;
        }, []);

        if (count($output) != 2) {
            $import = null;
            unlink(self::IMPORT_PATH);
            throw new Exception("Input file did not have expected headers 'name' and 'qb_code'");
        }

        return $output;
    }

    private function processLine(array $newLine, mixed $headers): bool
    {
        $clean = function ($string) {
            $string = trim($string, ' ');
            return empty($string) ? null : $string;
        };
        $data = [
            Fixture::QBC => $clean($newLine[$headers[Fixture::QBC]]),
            Fixture::N => $clean($newLine[$headers[Fixture::N]]),
        ];

        $entity = $this->Items->newEntity($data);
        if (
            $this->Items->save($entity)
            && $this->Items->Customers
                ->link($entity, [$this->customer]))
        {
            return true;
        }
        //(new DateTime())->firstOfMonth()->format('Y-m-d 00:00:01')
        return false;

//        $record = $this->Items->findOrCreate(
//            ['qb_code' => $newLine[$headers['qb_code']]],
//            function (Item $entity) use ($newLine, $headers) {
//                $entity->set('name', $newLine[$headers['name']]);
//                $entity->set('qb_code', $newLine[$headers['qb_code']]);
//            }
//        );
//        osd($record);
    }


}
