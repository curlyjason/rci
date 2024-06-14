<?php


namespace App\Utilities;



trait HashIdTrait
{

    public function idHash()
    {
        return BusinessRules::digest($this, self::DIGEST_COLUMNS);
    }

    public function isHash($hash)
    {
        return $hash === $this->idHash();
    }

}
