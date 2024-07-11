<?php


namespace App\Test\Traits;

use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;


trait MockModelTrait
{

    public function mockForSave($alias, $class, $callsExpected, $providedEntity = null)
    {
        /* @var TestCase $this */
        $mock = $this->getMockForModel($class, ['save']);
        $mock->expects($callsExpected)
            ->method('save')
            ->willReturn($this->getReturnEntity($providedEntity));
        $mock->setAlias($alias);
        $this->getTableLocator()->set($alias, $mock);
    }

    public function mockForFailedSave($alias, $class, $callsExpected)
    {
        /* @var TestCase $this */
        $mock = $this->getMockForModel($class, ['save']);
        $mock->expects($callsExpected)
            ->method('save')
            ->willReturn(false);
        $mock->setAlias($alias);
        return $this->getTableLocator()->set($alias, $mock);
    }

    public function mockForFailedDelete($alias, $class, $callsExpected)
    {
        /* @var TestCase $this */
        $mock = $this->getMockForModel($class, ['delete']);
        $mock->expects($callsExpected)
            ->method('delete')
            ->willReturn(false);
        $mock->setAlias($alias);
        return $this->getTableLocator()->set($alias, $mock);
    }

    /**
     * @return Entity
     */
    private function getReturnEntity($providedValue): Entity
    {
        return $providedValue ?? new Entity([]);
    }

}
