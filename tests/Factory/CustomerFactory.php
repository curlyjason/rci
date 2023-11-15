<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * CustomerFactory
 *
 * @method \App\Model\Entity\Customer getEntity()
 * @method \App\Model\Entity\Customer[] getEntities()
 * @method \App\Model\Entity\Customer|\App\Model\Entity\Customer[] persist()
 * @method static \App\Model\Entity\Customer get(mixed $primaryKey, array $options = [])
 */
class CustomerFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Customers';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'name' => $faker->company,
            ];
        });
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @param int $n
     * @return CustomerFactory
     */
    public function withItems($parameter = null, int $n = 1): CustomerFactory
    {
        return $this->with(
            'Items',
            ItemFactory::make($parameter, $n)->without('Customers')
        );
    }
}
