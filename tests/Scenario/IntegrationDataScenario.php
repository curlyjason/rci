<?php

namespace App\Test\Scenario;

use App\Test\Factory\CustomerFactory;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Factory\UserFactory;
use App\Test\Factory\VendorFactory;
use Cake\I18n\DateTime;

class IntegrationDataScenario implements \CakephpFixtureFactories\Scenario\FixtureScenarioInterface
{

    /**
     * @inheritDoc
     */
    public function load(...$args): mixed
    {
        $vendors = VendorFactory::make(3)->withItems(3)->persist();
        $customers = CustomerFactory::make(3)->persist();
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

        collection($vendors)
            ->map(function ($vendor, $vi) use ($customers, $cust_item, $CustTable) {
                $items = $vendor->items;
                foreach ($customers as $ci => $customer) {
                    $CustTable->patchEntity(
                        $cust_item[$vi][$ci],
                        [
                            'customer_id' => $customer->id,
                            'item_id' => $items[$ci]->id,
                            'quantity' => 5,
                            'trigger_quantity' => 10,
                            'next_inventory' => (new DateTime())->firstOfMonth()->format('Y-m-d 00:00:01'),
                        ]);
                    $CustTable->save($cust_item[$vi][$ci]);
                }
            })
            ->toArray();
        return null;
    }
}

