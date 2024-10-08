<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;

/**
 * CustomersItem Entity
 *
 * @property int $id
 * @property int|null $quantity
 * @property int|null $target_quantity
 * @property \Cake\I18n\DateTime|null $next_inventory
 * @property int $customer_id
 * @property int $item_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Item $item
 */
class CustomersItem extends Entity
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
        'quantity' => true,
        'target_quantity' => true,
        'next_inventory' => true,
        'customer_id' => true,
        'item_id' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'item' => true,
    ];

    protected array $_virtual = [
        'order_amount',
    ];

    /**
     * next_inventory in the future = inventory done for now
     *
     * @return bool
     */
    public function hasBeenInventoried(): bool
    {
        return new DateTime() < new DateTime($this->next_inventory);
    }

    public function _getOrderAmount()
    {
        return $this->target_quantity > $this->quantity
            ? $this->target_quantity - $this->quantity
            : 0;
    }
}
