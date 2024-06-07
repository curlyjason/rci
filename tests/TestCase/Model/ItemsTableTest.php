<?php

namespace App\Test\TestCase\Model;

use Cake\ORM\Locator\LocatorAwareTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class ItemsTableTest extends \Cake\TestSuite\TestCase
{

    use ScenarioAwareTrait;
    use LocatorAwareTrait;

    public function testFind()
    {
        $this->loadFixtureScenario('IntegrationData');
        $result = $this->fetchTable('Items')
            ->find()
            ->all()
            ->toArray();
        $this->assertTrue(count($result) > 0);
    }
}
