<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Constants\EmailCon;
use App\Utilities\HashIdTrait;
use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property int|null $customer_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 */
class User extends Entity
{

    use HashIdTrait;

    const DIGEST_COLUMNS = [
        'email' ,
        'id' ,
//        'verified', we need a way of activating and or verifying users
        'modified',
        'created'
    ];
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
        'email' => true,
        'password' => true,
        'customer_id' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }

    public function isAdmin()
    {
        return EmailCon::isAdmin($this->email);
    }
}
