<?php


namespace App\Test\Traits;


use App\Application;
use App\Model\Entity\User;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use Exception;

trait BaseEnvironment
{
    use LocatorAwareTrait;

    /**
     * Log in the first person found of a given role
     *
     * @param bool $role
     * @return User
     */
    /**
     * Pass in the id for appropriate linking
     *
     * @param $role
     * @param null $keyId
     * @throws Exception
     */
    public function login($admin = true)
    {
        $user = $this->fetch('Users')
            ->get($person->user->id, ['contain' => ['People']]);
        $session = Application::container()->get(ServerRequest::class)->getSession();
        $session(['Auth' => $user]);
        return $person;
    }


    public function postSecurity()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
    }

}
