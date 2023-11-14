<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * VendorFactory
 *
 * @method \App\Model\Entity\Vendor getEntity()
 * @method \App\Model\Entity\Vendor[] getEntities()
 * @method \App\Model\Entity\Vendor|\App\Model\Entity\Vendor[] persist()
 * @method static \App\Model\Entity\Vendor get(mixed $primaryKey, array $options = [])
 */
class VendorFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Vendors';
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
     * @return VendorFactory
     */
    public function withItems($parameter = null, int $n = 1): VendorFactory
    {
        return $this->with(
            'Items',
            ItemFactory::make($parameter, $n)->without('Vendors')
        );
    }
}
