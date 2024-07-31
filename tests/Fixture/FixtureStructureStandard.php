<?php

namespace App\Test\Fixture;

class FixtureStructureStandard
{

    protected static $orderPostArray = [
        'order_now' => false,
        'name' => '',
        'email' => 'jason@curlymedia.com',
        'order_quantity' => [5, 5, 5],
        'id' => [3, 6, 9]
    ];

    public static function orderPostKeys()
    {
        $keys = array_keys(self::$orderPostArray);
        return sort($keys);
    }
}
