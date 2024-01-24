<?php

namespace App\Utilities;

use App\Application;
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
    use FlashTrait;

    //<editor-fold desc="PATH CONSTANTS">
    public const BULK_IMPORT_ROOT = WWW_ROOT . 'bulk-import/';
    public const BULK_ARCHIVE_ROOT = self::BULK_IMPORT_ROOT . 'archive/';
    public const IMPORT_PATH = self::BULK_IMPORT_ROOT . 'import.txt';
    public const ERROR_PATH = self::BULK_IMPORT_ROOT . 'errors.txt';
    //</editor-fold>
    //<editor-fold desc="CONSTANTS">
    public const DUP = '@duplicate@';
    public const EDIT = '@edit@';
    public const NEW = '@new@';
    public const STATUS_REGEX = '^@(duplicate|edit|new)@';
    //</editor-fold>
    //<editor-fold desc="COMPOSED OBJECTS">
    private ServerRequest $request;
    private User $identity;
    public null|Customer|EntityInterface $customer;
    protected \Cake\ORM\Table|ItemsTable $Items;
    //</editor-fold>
    //<editor-fold desc="FILE STREAMS: 2 lifespans, request-processing and response-rendering">
    /**
     * @var resource|null|false
     */
    protected $source;
    /**
     * @var resource|null|false
     */
    public $archive;
    /**
     * @var resource|null|false
     */
    public $errors;
    //</editor-fold>
    //<editor-fold desc="LIFESPAN PROPERTIES: once set they never change">
    public const REQUIRED_HEADERS = [
        'name',
        'qb_code',
    ];
    public null|string $archivePath;
    private string $errorOutputPattern = "ERROR => %s - %s\n";
    public array $rawHeaders;
    protected array $headerMap;
    //</editor-fold>
    //<editor-fold desc="DYNAMIC PROPERTIES">
    public int $archiveCount = 0;
    public int $errorCount = 0;
    protected Item $workingEntity;
    //</editor-fold>
    protected mixed $itemQuery = null;

    public function __construct()
    {
        $this->request = Application::container()->get(ServerRequest::class);
        $this->identity = $this->request->getSession()->read('Auth');
        $this->customer = $this->fetchTable('Customers')
            ->get($this->identity->customer_id);
        $this->Items = $this->fetchTable('Items');
        $this->archivePath = $this->setImportArchivePath();
        $this->prepareMatchingItemQuery('dummy value');
    }

    public function processUploadFile(): void
    {
        //<editor-fold desc="LOCAL UTILITY FUNCTIONS">
        $initArchiveAndErrorFiles = function () {
            $headers = implode(',',$this->rawHeaders) . "\n";
            $this->archive = fopen($this->archivePath, 'w');
            fwrite($this->archive, $headers);
            $this->errors = fopen(self::ERROR_PATH, 'w');
            fwrite($this->errors, $headers);
        };
        $archive = function ($newLine, $status) {
            $this->archiveCount++;
            fwrite($this->archive, $status . implode(',', $newLine) . "\n");
        };
        $retainError = function ($newLine) {
            $this->errorCount++;
            fwrite($this->errors, implode(',', $newLine) . "\n");
            $errors = Hash::flatten($this->workingEntity->getErrors());
            foreach ($errors as $path => $error) {
                fwrite($this->errors, sprintf($this->errorOutputPattern, $path, $error));
            }
        };
        $prepareFlashResult = function () {
            if ((bool) $this->archiveCount) {
                $this->flashSuccess(
                    "Total items imported for {$this->customer->name}: {$this->archiveCount}",
                    "Import data archive at: {$this->archivePath}"
                );
            }
            if ((bool) $this->errorCount) {
                $this->flashError("Total lines with errors: {$this->errorCount}");
            }
        };
        //</editor-fold>

        try {
            $this->source = fopen(self::IMPORT_PATH, 'r');
            $this->mapHeaderPositions(); //throws exception
            $initArchiveAndErrorFiles();

            while ($newLine = fgetcsv($this->source)) {
                $status = $this->evaluateAgainstPersisted($newLine);
                if ($this->processLine($newLine, $status)) {
                    $archive($newLine, $status);
                } else {
                    $retainError($newLine);
                }
            }
            $prepareFlashResult();
        } catch (Exception $e) {
            $this->flashError($e->getMessage());
        }
        $this->closeResources();
        unlink(self::IMPORT_PATH);
    }

    private function processLine(array $inArray, string $status): bool
    {
        if ($status === self::DUP) {
            return true;
        }

        $data = [
            'qb_code' => $this->valueOf('qb_code', $inArray),
            'name' => $this->valueOf('name', $inArray),
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

    /**
     * Create map to the offset of column values
     *
     * [header-string => offset, ...]
     *
     * @return void
     * @throws Exception
     */
    private function mapHeaderPositions(): void
    {
        $this->rawHeaders = fgetcsv($this->source);

        $this->headerMap = collection($this->rawHeaders)->reduce(function ($accum, $value, $index) {
            $underscore = trim(Inflector::underscore($value));
            if (in_array($underscore, self::REQUIRED_HEADERS)) {
                $accum[$underscore] = $index;
            }

            return $accum;
        }, []);

        if (count($this->headerMap) != 2) {
            $this->source = null;
            unlink(self::IMPORT_PATH);
            throw new Exception("Input file did not have expected headers 'name' and 'qb_code'");
        }
    }

    private function setImportArchivePath(): string
    {
        return self::BULK_ARCHIVE_ROOT . time() . '.txt';
    }

    /**
     * Get input value w/leading-trailing spaces trimmed
     *
     * @param string $key
     * @param array $data
     * @return string|null
     */
    private function valueOf(string $key, array $data): ?string {
        $string = trim($data[$this->headerMap[$key]] ?? '', ' ');
        return empty($string) ? null : $string;  }

    /**
     * sets property $archive
     *
     * @return false|resource|null
     */
    public function openArchiveToRead()
    {
        $this->archive = fopen($this->archivePath, 'r');
        return $this->archive;
    }

    /**
     * sets property $errors
     *
     * @return false|resource|null
     */
    public function openErrorsToRead()
    {
        $this->errors = fopen(self::ERROR_PATH, 'r');
        return $this->errors;
    }

    private function evaluateAgainstPersisted(array $line): string
    {
        $qb_code = $this->valueOf('qb_code', $line);

        $result = $this->Items->getConnection()->execute(
            $this->itemQuery,
            [':c0' => $this->customer->id, ':c1' => $qb_code,],
            [':c0' => 'integer', ':c1' => 'string']
        )->fetchAssoc();

        return match(true) {
            empty($result) => self::NEW,
            $this->valueOf('name', $line) === $result['Items__name'] => self::DUP,
            default => self::EDIT,
        };
    }

    public function closeResources(): void
    {
        $this->archive = $this->errors = $this->source = null;
    }

    protected function prepareMatchingItemQuery(mixed $qb_code)
    {
        $this->itemQuery = $this->Items->findExistingCustomerItem(
            $qb_code, $this->customer->id
        )->sql();
    }
}
