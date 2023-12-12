<?php
declare(strict_types=1);

namespace App\Controller;

use App\Test\Factory\CustomerFactory;
use App\Test\Scenario\IntegrationDataScenario;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property AuthenticationComponent Authentication
 */
class CustomersController extends AppController
{
    use ScenarioAwareTrait;

   public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        if (Configure::read('debug')) {
            $this->Authentication->allowUnauthenticated(['init']);
        }
    }

    public function init()
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        return $this->redirect('/');
    }
}
