<?php

namespace App\Test\Traits;


use File;

trait DebugTrait
{
    public function writeFile($name = 'debug')
    {
        $file_name = "$name.html";
        $file = fopen(WWW_ROOT . $file_name, 'w');
        $result = fwrite($file,$this->_getBodyAsString());
        debug($result ? 'http://localhost:8015/' . $file_name : "failed to write $file_name");
        fclose($file);
    }

}
