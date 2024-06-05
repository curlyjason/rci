<?php
declare(strict_types=1);

namespace App\Controller;

use App\Application;
use App\Forms\OrderNowForm;
use App\Model\Entity\Order;
use App\Utilities\CustomerFocus;
use App\Utilities\DateUtilityTrait;
use App\Utilities\UserMailer;
use Cake\Http\Response;

/**
 * CustomersItems Controller
 *
 * @property \App\Model\Table\CustomersItemsTable $CustomersItems
 */
class CustomersItemsController extends AppController
{
    use DateUtilityTrait;

    protected array $paginate = [
        'limit' => 100,
    ];

    public function takeInventory(): Response
    {
        /**
         * insure we have customer focus
         */
        if (!(Application::container()->get(CustomerFocus::class))->focus($this)) {
            return $this->render(CustomerFocus::TEMPLATE_SET_CUSTOMER_FOCUS);
        }
        /**
         * render take-inventory UI
         */
        $this->setUserCustomerVariable();

        $customersItems = $this->paginate(
            $this->CustomersItems->findInventoryForCustomer(
                $this->readSession('Auth')->customer_id
            )
        );
        $nextInventoryDate = $this->nextMonthsInventoryDate(self::DATE_FORMAT_YYYY_MM_DD);

        $this->set(compact('customersItems', 'nextInventoryDate'));

        return $this->render();
    }

    public function setTriggerLevels(): Response
    {
        /**
         * insure we have customer focus
         */
        if (!(Application::container()->get(CustomerFocus::class))->focus($this)) {
            return $this->render(CustomerFocus::TEMPLATE_SET_CUSTOMER_FOCUS);
        }
        /**
         * render set-trigger-level UI
         */
        $this->set($this->createItemListAndFilterMap()); //customersItems, masterFilterMap

        return $this->render();
    }

    public function orderNow(): Response
    {
        /**
         * Insure we have customer focus
         */
        if (!(Application::container()->get(CustomerFocus::class))->focus($this)) {
            return $this->render(CustomerFocus::TEMPLATE_SET_CUSTOMER_FOCUS);
        }
        /**
         * Process a post if we have one
         */
        $Form = Application::container()->get(OrderNowForm::class);
        if ($Form->execute($this->request->getData())) {
            /**
             * Either leave after successful save or go to fix-errors UI
             */
            return $this->render($this->saveNewOrder($Form));
        }
        /**
         * Render the Order Now form
         */
        $this->set($this->createItemListAndFilterMap()); //customersItems, masterFilterMap

        return $this->render();
    }

    /**
     * @return void
     */
    private function setUserCustomerVariable(): void
    {
        $user = $this->fetchTable('Users')
            ->find()
            ->where(['Users.id' => $this->readSession('Auth')->id])
            ->contain(['Customers'])
            ->first();

        $this->set(compact('user'));
    }

    /**
     * @return mixed
     */
    private function createItemListAndFilterMap(): mixed
    {
        $accum = [
            'masterFilterMap' => new \stdClass(), //for javascript live filtering tool
            'customersItems' => $this->GetPaginatedItemsForUser(), //data for rendering
        ];

        return collection($accum['customersItems'])
            ->reduce(function ($accum, $customerItem) {
                $id = $customerItem->id;
                $accum['masterFilterMap']->$id = $customerItem->item->name;

                return $accum;
            }, $accum);
    }

    /**
     * @return \Cake\Datasource\Paging\PaginatedInterface
     */
    private function GetPaginatedItemsForUser(): \Cake\Datasource\Paging\PaginatedInterface
    {
        $this->setUserCustomerVariable();
        $query = $this->CustomersItems->find()
            ->where(['customer_id' => $this->readSession('Auth')->customer_id])
            ->contain(['Customers', 'Items']);
        return $this->paginate($query);
    }

    private function saveNewOrder(OrderNowForm $executedForm): string
    {
        $order = $executedForm->getData('result');
        /* @var Order $order */

        if (!$order->hasErrors() && $this->fetchTable('Orders')->save($order)) {
            $this->Flash->success('The order has been saved');

            return '/Pages/home';
        }
        $this->Flash->error('The order has not been saved');
        $this->set(compact('executedForm'));

        return '/Admin/Orders/resolve-errors';
    }

    public function sendReplenishInventory()
    {
        $mailer = new UserMailer('default');
        $mailer
            ->setEmailFormat('text')
            ->setFrom(['jason@curlymedia.com' => 'Curly Media'])
            ->setTo('jason@tempestinis.com')
            ->setSubject('About');
        osd($mailer);
        $mailer
            ->deliver('My message');
    }

}
