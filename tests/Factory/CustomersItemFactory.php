<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * CustomersItemFactory
 *
 * @method \App\Model\Entity\CustomersItem getEntity()
 * @method \App\Model\Entity\CustomersItem[] getEntities()
 * @method \App\Model\Entity\CustomersItem|\App\Model\Entity\CustomersItem[] persist()
 * @method static \App\Model\Entity\CustomersItem get(mixed $primaryKey, array $options = [])
 */
class CustomersItemFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'CustomersItems';
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
     * @return CustomersItemFactory
     */
    public function withCustomers($parameter = null): CustomersItemFactory
    {
        return $this->with(
            'Customers',
            CustomerFactory::make($parameter)
        );
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @return CustomersItemFactory
     */
    public function withItems($parameter = null): CustomersItemFactory
    {
        return $this->with(
            'Items',
            ItemFactory::make($parameter)
        );
    }
}
