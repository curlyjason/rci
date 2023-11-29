<?php

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

require_once CONFIG . 'conv.php';

class SeedDataCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $curl = curl_init(sprintf('http://localhost:%s/customers/init',WEB_PORT));
        curl_exec($curl);
        curl_close($curl);

        parent::execute($args, $io);
    }



}
