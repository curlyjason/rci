<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property string|null $order_number
 * @property string|null $ordered_by
 * @property string|null $ordered_by_email
 * @property string|null $status
 * @property \Cake\I18n\Date|null $order_date
 * @property \Cake\I18n\Date|null $due_date
 * @property \Cake\I18n\Date|null $ship_date
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class Order extends Entity
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
        'order_lines' => true,
        'order_number' => true,
        'ordered_by' => true,
        'ordered_by_email' => true,
        'status' => true,
        'order_date' => true,
        'due_date' => true,
        'ship_date' => true,
        'created' => true,
        'modified' => true,
    ];
}
