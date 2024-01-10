<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * OrderFactory
 *
 * @method \App\Model\Entity\Order getEntity()
 * @method \App\Model\Entity\Order[] getEntities()
 * @method \App\Model\Entity\Order|\App\Model\Entity\Order[] persist()
 * @method static \App\Model\Entity\Order get(mixed $primaryKey, array $options = [])
 */
class OrderFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Orders';
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
                // set the model's default values
                // For example:
                // 'name' => $faker->lastName
            ];
        });
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @param int $n
     * @return OrderFactory
     */
    public function withOrderLines($parameter = null, int $n = 1): OrderFactory
    {
        return $this->with(
            'OrderLines',
            OrderLineFactory::make($parameter, $n)
        );
    }
}
