<?php

namespace App\Test\Fixture;

use Cake\TestSuite\TestCase;

class FixtureStructureStandard extends TestCase
{

    //<editor-fold desc="EXPECTED POST DATA ARRAY STRUCTURES">
    /**
     * post array structure for /order-now
     */
    protected static $ajax_orderNow = [
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

    /**
     * post array structure for ajax/set-trigger-levels
     */
    protected static array $ajax_setTriggerLevel = [
        'id' =>  '7' ,
        'target_quantity' =>  '52' ,
    ];
    //</editor-fold>

    public static function assertKeysMatch_orderNow($sut, $message = '')
    {
        $keys_under_test = self::getSortedKeys($sut);
        $defined_keys = self::getSortedKeys(self::$ajax_orderNow);

        self::assertEquals($defined_keys, $keys_under_test, $message);
    }

    public static function assertKeysMatch_setInventory($sut, $message = '')
    {
        $keys_under_test = self::getSortedKeys($sut);
        $defined_keys = self::getSortedKeys(self::$ajax_setInventory);

        self::assertEquals($defined_keys, $keys_under_test, $message);
    }

    public static function assertKeysMatch_setTriggerLevel($sut, $message = '')
    {
        $keys_under_test = self::getSortedKeys($sut);
        $defined_keys = self::getSortedKeys(self::$ajax_setTriggerLevel);

        self::assertEquals($defined_keys, $keys_under_test, $message);
    }

    private static function getSortedKeys($sut)
    {
        $keys = array_keys($sut);
        sort($keys);

        return $keys;
    }
}
