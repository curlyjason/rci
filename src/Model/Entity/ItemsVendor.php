<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemsVendor Entity
 *
 * @property int $id
 * @property string|null $sku
 * @property int $item_id
 * @property int $vendor_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\Vendor $vendor
 */
class ItemsVendor extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'sku' => true,
        'item_id' => true,
        'vendor_id' => true,
        'created' => true,
        'modified' => true,
        'item' => true,
        'vendor' => true,
    ];
}
