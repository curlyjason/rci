<?php


namespace App\Utilities;


use Cake\Mailer\Mailer;

class ProvideMailer
{

    private static $Mailer;

    private function __construct() { }

    public static function instance($config = []): Mailer
    {
        $config = empty($config) ? 'default' : $config;

        if (!isset(self::$Mailer)) {
            self::$Mailer = new Mailer($config);
        }
        return self::$Mailer;
    }

    public static function inject(Mailer $Mailer)
    {
        self::$Mailer = $Mailer;
    }

}
