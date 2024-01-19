<?php

namespace App\Utilities;

use Cake\Controller\Component\FlashComponent;

trait FlashTrait
{
    public array $flash = [
        'success' => [],
        'error' => [],
    ];

    public function flashError(...$message)
    {
        $this->flash['error'] = array_merge($this->flash['error'], $message);
    }

    public function flashSuccess(...$message)
    {
        $this->flash['success'] = array_merge($this->flash['success'], $message);
    }

    public function flashOutput(FlashComponent $Flash): void
    {
        foreach ($this->flash as $type => $messageSet) {
            foreach ($messageSet as $message) {
                $Flash->$type($message);
            }
        }
    }

}
