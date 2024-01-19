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
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Exception;

class ImportItems
{
    use LocatorAwareTrait;

    //<editor-fold desc="PATH CONSTANTS">
    public const BULK_IMPORT_ROOT = WWW_ROOT . 'bulk-import/';
    public const BULK_ARCHIVE_ROOT = self::BULK_IMPORT_ROOT . 'archive/';
    public const IMPORT_PATH = self::BULK_IMPORT_ROOT . 'import.txt';
    public const ERROR_PATH = self::BULK_IMPORT_ROOT . 'errors.txt';
    //</editor-fold>
    //<editor-fold desc="COMPOSED OBJECTS">
    private ServerRequest $request;
    private User $identity;
    public null|Customer|EntityInterface $customer;
    private \Cake\ORM\Table|ItemsTable $Items;
    //</editor-fold>
    //<editor-fold desc="FILE STREAMS: 2 lifespans, request-processing and response-rendering">
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
    //</editor-fold>
    //<editor-fold desc="LIFESPAN PROPERTIES: once set they never change">
    public const REQUIRED_HEADERS = [
        'name',
        'qb_code',
    ];
    protected array $rawHeaders;
    public null|string $archivePath;
    //</editor-fold>
    //<editor-fold desc="DYNAMIC PROPERTIES">
    public int $archiveCount = 0;
    public int $errorCount = 0;
    private Item $workingEntity;
    public array $flash = [
        'success' => [],
        'error' => [],
    ];
    //</editor-fold>

    public function __construct()
    {
        $this->request = Application::container()->get(ServerRequest::class);
        $this->identity = $this->request->getSession()->read('Auth');
        $this->customer = $this->fetchTable('Customers')
            ->get($this->identity->customer_id);
        $this->Items = $this->fetchTable('Items');
    }

    public function processUploadFile()
    {
        $initArchiveAndErrorFiles = function () {
            $this->archive = fopen($this->archivePath, 'w');
            $this->errors = fopen(self::ERROR_PATH, 'w');
            fwrite($this->errors, implode(',',$this->rawHeaders) . "\n");
        };
        $archive = function ($newLine) {
            $this->archiveCount++;
            fwrite($this->archive, implode(',', $newLine) . "\n");
        };
        $retainError = function ($newLine) {
            $this->errorCount++;
            fwrite($this->errors, implode(',', $newLine) . "\n");
            $errors = Hash::flatten($this->workingEntity->getErrors());
            fwrite($this->errors, var_export($errors, true) . "\n");
        };

        try {
            $this->source = fopen(self::IMPORT_PATH, 'r');
            $this->archivePath = $this->getImportArchivePath();

            //read in the headers, throws exception
            $headers = $this->checkHeaders();

            $initArchiveAndErrorFiles();

            while ($newLine = fgetcsv($this->source)) {
                if ($this->processLine($newLine, $headers)) {
                    $archive($newLine);
                } else {
                    $retainError($newLine);
                }
            }
        } catch (Exception $e) {
            $this->flash['error'][] = ($e->getMessage());
        }
    }

    private function checkHeaders()
    {
        $this->rawHeaders = fgetcsv($this->source);

        $output = collection($this->rawHeaders)->reduce(function ($accum, $value, $index) {
            $underscore = trim(Inflector::underscore($value));
            if (in_array($underscore, self::REQUIRED_HEADERS)) {
                $accum[$underscore] = $index;
            }

            return $accum;
        }, []);

        if (count($output) != 2) {
            $this->source = null;
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
            'joins' => [
                [
                    'next_inventory' => (new DateTime())->firstOfMonth()->format('Y-m-d 00:00:01'),
                    'customer_id' => $this->customer->id,
                    'target_quantity' => 1,
                ]
            ],
        ];
        $this->workingEntity = $this->Items->newEntity($data);

        return (bool) $this->Items->save($this->workingEntity);
    }

    private function getImportArchivePath(): string
    {
        return self::BULK_ARCHIVE_ROOT . time();
    }
}
