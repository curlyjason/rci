<?php

namespace App\Test\Traits;


use Cake\Mailer\Message;
require_once(CONFIG . DS . 'conv.php');

trait DebugTrait
{
    public function writeFile($name = 'debug')
    {
        $file_name = "$name.html";
        $file = fopen(WWW_ROOT . $file_name, 'w');
        $result = fwrite($file,$this->_getBodyAsString());
        $web_port = WEB_PORT;
        debug($result ? "http://localhost:$web_port/" . $file_name : "failed to write $file_name");
        fclose($file);
    }


    /**
     * @param Message[] $messages
     * @return void
     */
    public function writeEmails(array $messages)
    {
        foreach ($messages as $index => $message) {
            $file_name = "message$index.html";
            $file = fopen(WWW_ROOT . $file_name, 'w');
            $result = fwrite($file,$message->getBodyString());
            $web_port = WEB_PORT;
            debug($result ? "http://localhost:$web_port/" . $file_name : "failed to write $file_name");
            fclose($file);
        }
    }

}
