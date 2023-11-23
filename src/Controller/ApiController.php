<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\View\JsonView;
use Exception;
use stdClass;

class ApiController extends \App\Controller\AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('ajax');
        $this->viewBuilder()->setTemplate('/Api/output');
    }

    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    /**
     * @param Exception $e
     * @return stdClass
     */
    protected function encodeExceptionForJson(Exception $e): stdClass
    {
        $exception_basics = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
        ];
        $exception_details = Configure::read('debug')
            ? [
                'line' => $e->getFile() . $e->getLine(),
                'trace' => $e->getTrace(),
                'tracestring' => $e->getTraceAsString(),
            ]
            : [];

        $nodes = new stdClass();
        $nodes->error = array_merge($exception_basics, $exception_details);

        return $nodes;
    }

}
