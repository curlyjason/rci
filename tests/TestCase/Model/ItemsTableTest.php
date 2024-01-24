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
        $this->fetchTable('Items')
            ->find()
            ->all();
    }
}
