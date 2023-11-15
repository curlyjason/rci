<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ItemFactory
 *
 * @method \App\Model\Entity\Item getEntity()
 * @method \App\Model\Entity\Item[] getEntities()
 * @method \App\Model\Entity\Item|\App\Model\Entity\Item[] persist()
 * @method static \App\Model\Entity\Item get(mixed $primaryKey, array $options = [])
 */
class ItemFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Items';
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
                'name' => $faker->sentence(6),
            ];
        });
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @param int $n
     * @return ItemFactory
     */
    public function withCustomers($parameter = null, int $n = 1): ItemFactory
    {
        return $this->with(
            'Customers',
            CustomerFactory::make($parameter, $n)->without('Items')
        );
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @param int $n
     * @return ItemFactory
     */
    public function withVendors($parameter = null, int $n = 1): ItemFactory
    {
        return $this->with(
            'Vendors',
            VendorFactory::make($parameter, $n)->without('Items')
        );
    }
}
