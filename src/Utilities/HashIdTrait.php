<?php


namespace App\Utilities;



trait HashIdTrait
{

    public function getDigest()
    {
        return BusinessRules::digest($this, self::DIGEST_COLUMNS);
    }

    public function digestIs($hash)
    {
        return $hash === $this->getDigest();
    }

}
