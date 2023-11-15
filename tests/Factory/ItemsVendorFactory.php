<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ItemsVendorFactory
 *
 * @method \App\Model\Entity\ItemsVendor getEntity()
 * @method \App\Model\Entity\ItemsVendor[] getEntities()
 * @method \App\Model\Entity\ItemsVendor|\App\Model\Entity\ItemsVendor[] persist()
 * @method static \App\Model\Entity\ItemsVendor get(mixed $primaryKey, array $options = [])
 */
class ItemsVendorFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'ItemsVendors';
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
                'sku' => uniqid('sku-'),
            ];
        });
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @return ItemsVendorFactory
     */
    public function withItems($parameter = null): ItemsVendorFactory
    {
        return $this->with(
            'Items',
            ItemFactory::make($parameter)
        );
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @return ItemsVendorFactory
     */
    public function withVendors($parameter = null): ItemsVendorFactory
    {
        return $this->with(
            'Vendors',
            VendorFactory::make($parameter)
        );
    }
}
