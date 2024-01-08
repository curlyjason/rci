<?php
declare(strict_types=1);

namespace App\Test\Scenario;

use App\Test\Factory\CustomerFactory;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Factory\ItemFactory;
use App\Test\Factory\UserFactory;
use App\Test\Factory\VendorFactory;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;

class IntegrationDataScenario implements FixtureScenarioInterface
{
    public $item_names = [
        'T46Y100 P900 50ml Photo Black',
        'T46Y200 P900 50ml Photo Cyan',
        'T46Y300 P900 50ml Photo Magenta',
        'T46Y400 P900 50ml Photo Yellow',
        'T46Y500 P900 50ml Photo Lite Cyan',
        'T46Y600 P900 50ml Photo Lite Magenta',
        'T46Y700 P900 50ml Photo Gray',
        'T46Y800 P900 50ml Ultrachrome PRO10 Matte Black',
        'T46Y900 P900 50ml Ultrachrome PRO10 Light Gray',
        'T46YD00 P900 50ml Ultrachrome PRO10 Violet',
        'C12C935711 P900 Maintenance Tank',
        '1319-1 MS Premium Pearl 13x19 (100 sheets)',
        'PLQP26544100 MS Premium Luster 265 44in roll',
    ];

    /**
     * @inheritDoc
     */
    public function load(...$args): mixed
    {
        $items = ItemFactory::make(9)->persist();
        $customers = CustomerFactory::make(3)->withUsers(1)->persist();
        $cust_item = array_chunk(CustomersItemFactory::make(9)->getEntities(), 3);
        UserFactory::make([
            [
                'email' => 'ddrake@dreamingmind.com',
                'password' => 'xx',
                'customer_id' => $customers[0]->id,
            ],
            [
                'email' => 'jason@curlymedia.com',
                'password' => 'xx',
                'customer_id' => $customers[0]->id,
            ],
        ])
        ->persist();
        /**
         * $cust_item = [
         *   [entity, entity, entity],
         *   [entity, entity, entity],
         *   [entity, entity, entity],
         * ];
         */
        $CustTable = CustomersItemFactory::make()->getTable();

        collection(array_chunk($items, 3))
            ->map(function ($_3items, $index) use ($customers, $cust_item, $CustTable) {
                $items = $_3items;
                foreach ($customers as $ci => $customer) {
                    $CustTable->patchEntity(
                        $cust_item[$index][$ci],
                        [
                            'customer_id' => $customer->id,
                            'item_id' => $items[$ci]->id,
                            'quantity' => 5,
                            'trigger_quantity' => 10,
                            'next_inventory' => (new DateTime())->firstOfMonth()->format('Y-m-d 00:00:01'),
                        ]
                    );
                    $CustTable->save($cust_item[$index][$ci]);
                }
            })
            ->toArray();
        $this->addNamedItems($customers[0]);

        return null;
    }

    private function addNamedItems($customer)
    {
        collection($this->item_names)
            ->map(function ($item) use ($customer) {
                $i = ItemFactory::make(['name' => $item])->persist();
                CustomersItemFactory::make([
                    'customer_id' => $customer->id,
                    'item_id' => $i->id,
                    'quantity' => 5,
                    'trigger_quantity' => 10,
                    'next_inventory' => (new DateTime())->firstOfMonth()->format('Y-m-d 00:00:01'),
                ])->persist();
            })
            ->toArray();
    }
}
