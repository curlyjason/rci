<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\Admin\ItemsController;
use App\Utilities\ImportItems;
use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
    }

    /**
     * ['success' => [messages, ...], 'error' => [messages, ...]
     *
     * @param ImportItems $importer
     * @return void
     */
    public function flashOutput(array $data): void
    {
        foreach ($data as $type => $messageSet) {
            foreach ($messageSet as $message) {
                $this->Flash->$type($message);
            }
        }
    }

    protected function readSession(?string $string = null, mixed $default = null)
    {
        return $this->request->getSession()->read($string, $default);
    }

    protected function isAdmin() {
        return $this->readSession('Auth')->isAdmin();
    }
    protected function getIdentity() {
        return $this->readSession('Auth');
    }
}
