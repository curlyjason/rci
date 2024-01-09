<?php
declare(strict_types=1);

namespace App\Controller;

use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property AuthenticationComponent $Authentication
 */
class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['add', 'login']);
    }


    public function login()
    {
        $result = $this->Authentication->getResult();
        // If the user is logged in send them away.
        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/take-inventory';
            return $this->redirect($target);
        }
        if ($this->request->is('post')) {
            $this->Flash->error('Invalid username or password');
        }
    }

    public function logout()
    {
        $this->Authentication->logout();
        $this->request->getSession()->delete('Focus');
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

}
