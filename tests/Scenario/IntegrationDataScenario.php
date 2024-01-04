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
        $c = [];
        $i = [];
        foreach ([0,1,2] as $ix => $val) {
            $c[$ix] = CustomerFactory::make(1)->persist();
            $i[$ix] = ItemFactory::make(3)->withCustomers($c[$ix])->persist();
        }
        $customer = $c[0];
        $this->addNamedItems($customer);

        UserFactory::make([
            [
                'email' => 'ddrake@dreamingmind.com',
                'password' => 'xx',
                'customer_id' => $customer->id,
            ],
            [
                'email' => 'jason@curlymedia.com',
                'password' => 'xx',
                'customer_id' => $customer->id,
            ],
        ])
        ->persist();

        return null;
    }

    private function addNamedItems($customer)
    {
        collection($this->item_names)
            ->map(function ($item) use ($customer) {
                $i = ItemFactory::make(['name' => $item])->withCustomers($customer)->persist();
            })
            ->toArray();
    }
}
