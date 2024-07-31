<?php

namespace App\Test\TestCase\Controller;

use App\Test\Factory\CustomerFactory;
use App\Test\Factory\CustomersItemFactory;
use App\Test\Fixture\FixtureStructureStandard;
use App\Test\Traits\AuthTrait;
use App\Test\Traits\DebugTrait;
use App\Test\Traits\MockModelTrait;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\IntegrationTestTrait;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class AjaxEndpointsTest extends \Cake\TestSuite\TestCase
{
    use IntegrationTestTrait;
    use ScenarioAwareTrait;
    use AuthTrait;
    use DebugTrait;
    use MockModelTrait;
    use TruncateDirtyTables;
    use EmailTrait;

    public function test_api_setInventory()
    {
        $this->loadFixtureScenario('IntegrationData');
        $this->login();
        $item = $this->getFirstItemForLoggedInUser();
        $postData = [
            'id' =>  $item->id ,
            'quantity' =>  '52' ,
        ];
        FixtureStructureStandard::assertKeysMatch_setInventory($postData,
            'setInventory POST-array keys don\'t match keys in the standard reference array');

        $this->enableCsrfToken();

        $this->post('api/set-inventory', $postData);
//        debug($this->_response);
//        $this->writeFile();

        $this->assertResponseCode(200);
    }

    /**
     * @return mixed
     */
    private function getFirstItemForLoggedInUser(): mixed
    {
        $item = CustomersItemFactory::make()->getTable()
            ->find()
            ->where(['customer_id' => $this->_session['Auth']->customer_id])
            ->first();
        return $item;
    }

}
