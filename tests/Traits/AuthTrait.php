<?php

namespace App\Test\Traits;

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
        $user = $this->getTableLocator()
            ->get('Users')
            ->find()
            ->where(['email' => $role])
            ->all()
            ->first();

        $this->session(['Auth' => $user]);
    }
}
