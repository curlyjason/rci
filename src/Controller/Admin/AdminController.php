<?php

namespace App\Controller\Admin;

use App\Model\Entity\Item;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\DateUtilityTrait;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Event\EventInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use mysql_xdevapi\Collection;

/**
 * @property AuthenticationComponent $Authentication
*/

class AdminController extends \App\Controller\AppController
{
    use LocatorAwareTrait;
    use DateUtilityTrait;
    public function inventoryReporter()
    {
        $customer_id = $this->request->getSession()->read('Auth.customer_id');
        $user = $this->request->getSession()->read('Auth');
        $Customer = $this->fetchTable('Customers');
        $customer = $Customer->get($customer_id);

        $inventoryReporter = new CustomerInventoryStatusReporter($customer);
        $this->set(compact('inventoryReporter', 'user'));
    }

    public function resetInventory()
    {
        $customer_id = $this->request->getSession()->read('Auth.customer_id');
        $CustomersItems = $this->fetchTable('CustomersItems');
        $items = $CustomersItems->find()->where(['customer_id'=>$customer_id])->all();
        $itemCollection = \collection($items)->map(function($item) use($CustomersItems){
            /* @var Item $item */
            $item->set('next_inventory', $this->thisMonthsInventoryDate());
            $CustomersItems->save($item);
            return $item;
        })->toArray();
        $this->redirect('take-inventory');
    }
}
