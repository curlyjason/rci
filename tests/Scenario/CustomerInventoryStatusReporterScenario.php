<?php

namespace App\Test\Scenario;

use App\Model\Entity\CustomersItem;
use App\Test\Factory\CustomerFactory;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Traits\RetrievalTrait;
use App\Utilities\CustomerInventoryStatusReporter;
use App\Utilities\DateUtilityTrait;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class CustomerInventoryStatusReporterScenario implements FixtureScenarioInterface
{
    use RetrievalTrait;
    use DateUtilityTrait;
    use ScenarioAwareTrait;

    /**
     * Create/return a CustomerStatusReporter variant
     *
     * The $arg['variant'] lets you control the structure of the 'reporter' class
     * The $arg['customer_id']
     */
    public function load(...$args): mixed
    {
//        debug($args['variant'] ?? 'missing');
//        debug($args);
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        $items = $this->getRecords('CustomersItems');
        /* @var CustomersItem $item */
        $updatedItems = collection($items)
            ->map(function ($item) {
                return $item->set('next_inventory', $this->nextMonthsInventoryDate());
            })
            ->toArray();
        CustomersItemFactory::make()->getTable()->saveMany($updatedItems);

        $customer = CustomerFactory::make()->getTable()->find()->contain(['Users'])->first();
//        debug($customer);die;

        return new CustomerInventoryStatusReporter($customer);
    }


}
