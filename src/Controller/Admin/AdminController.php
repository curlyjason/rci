<?php

namespace App\Controller\Admin;

use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Event\EventInterface;

/**
 * @property AuthenticationComponent $Authentication
*/

class AdminController extends \App\Controller\AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
//        $this->Authentication->getIdentity()->;
    }

}
