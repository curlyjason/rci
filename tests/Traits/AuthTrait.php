<?php

namespace App\Test\Traits;

use App\Model\Entity\User;
use Cake\TestSuite\TestCase;

trait AuthTrait
{
    /**
     * Data comes from Tests\Scenario\IntegrationDataScenario
     */
    public const ADMIN_USER = 'ddrake@dreamingmind.com';
    public const USER = 'prudence56@yahoo.com';
    public const ALL_ROLES = [self::USER, self::ADMIN_USER];

    public function login($role = self::USER): void
    {
        $user = $this->getUser($role);

        $this->session(['Auth' => $user]);
    }

    /**
     * @param mixed $role
     * @return mixed
     */
    private function getUser(mixed $role = self::USER): User
    {
        $condition = $role === self::ADMIN_USER ? ['email' => $role] : [];
        return $this->getTableLocator()
            ->get('Users')
            ->find()
            ->where($condition)
            ->all()
            ->first();
    }
}
