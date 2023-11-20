<?php

namespace App\Controller;

use Cake\View\JsonView;

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

}
