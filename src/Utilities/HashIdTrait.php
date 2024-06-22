<?php


namespace App\Utilities;



trait HashIdTrait
{

    public function getDigest(): string
    {
        return BusinessRules::digest($this, self::DIGEST_COLUMNS);
    }

    public function digestIs($hash): bool
    {
        return $hash === $this->getDigest();
    }

}
