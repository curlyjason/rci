<?php
declare(strict_types=1);

namespace App\Controller;

use App\Forms\ResetPasswordForm;
use App\Model\Entity\User;
use Authentication\Controller\Component\AuthenticationComponent;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Log\Log;

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
        $this->Authentication->allowUnauthenticated(['add', 'login', 'resetPassword', 'forgotPassword']);
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

    public function resetPassword($username, $hash, ResetPasswordForm $context)
    {
        /**
         * @var User $User
         */
        $User = $this->Users->find('all')
            ->where([
                'username' => $username,
                'active' => 1
            ])
            ->first();

        if(is_null($User)){
            $this->Flash->error("The chosen user does not exist.");
            return $this->logout();
        }

//        if(!$User->isHash($hash)){
//            $this->Flash->error("The chosen user is not valid.");
//            return $this->logout();
//        }

        if($User->modified->timestamp < time() - (60*60*24)){
            $this->Flash->error("The link has expired. Please request another.");
            return $this->logout();
        }

//        $context = new ResetPasswordForm(); went to Dependency Injection

        if($this->getRequest()->is('post') && $context->execute($this->getRequest()->getData())){
            $data = $this->getRequest()->getData();
            $Hash = new DefaultPasswordHasher();
            $data = [
                'password' => $Hash->hash($data['new_password']),
                'verified' => true,
                'active' => 1
            ];
            $this->Users->patchEntity($User, $data);
            $this->Users->save($User);
            if($User){
                $this->Flash->success('Password reset, please log in');
                return $this->logout();
            }
            else {
                $this->Flash->error('Password did not save, please try again.');
                Log::error("$User->username could not reset password.");
            }
        }
        $this->set(compact('User', 'context'));
    }

    public function forgotPassword()
    {
        if($this->getRequest()->is('post')){
            $User = $this->Users->find('all')
                ->where([
                    'username' => $this->getRequest()->getData('email'),
                    'active' => 1
                ])
                ->first();
            if(!is_null($User)){
                $this->Flash->success("Reset password link has been emailed to $User->username. Please follow the instructions.");
                $this->Users->patchEntity($User, ['modified' => time()]);
                $this->Users->save($User);
//                $this->trigger('resetPasswordNotification', ['User' => $User, 'new' => false]);
            }
            else {
                $this->Flash->error("No user found with that email address");
            }
            $this->logout();

        }
    }

}
