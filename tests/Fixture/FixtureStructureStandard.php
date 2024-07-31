<?php

namespace App\Test\Fixture;

use Cake\TestSuite\TestCase;

class FixtureStructureStandard extends TestCase
{

    /**
     * post array structure for /order-now
     */
    protected static $orderPostArray = [
        'order_now' => false,
        'name' => '',
        'email' => 'jason@curlymedia.com',
        'order_quantity' => [5, 5, 5],
        'id' => [3, 6, 9]
    ];

    /**
     * post array structure for ajax/set-inventory
     */
    protected static array $ajax_setInventory = [
        'id' =>  '7' ,
        'quantity' =>  '52' ,
    ];

    public static function assertKeysMatch_orderNow($sut, $message = '')
    {
        $keys_under_test = self::getSortedKeys($sut);
        $defined_keys = self::getSortedKeys(self::$orderPostArray);

        self::assertEquals($defined_keys, $keys_under_test, $message);
    }

    public static function assertKeysMatch_setInventory($sut, $message = '')
    {
        $keys_under_test = self::getSortedKeys($sut);
        $defined_keys = self::getSortedKeys(self::$ajax_setInventory);

        self::assertEquals($defined_keys, $keys_under_test, $message);
    }

    private static function getSortedKeys($sut)
    {
        $keys = array_keys($sut);
        return sort($keys);
    }
}
