<?php

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class SeedDataCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        parent::execute($args, $io);
        $user = $io->askChoice("Jason or Don", ['Jason', 'Don'], 'don');
        if ($user === 'Jason' || $user === 'jason') {
            $port = '8035';
        }
        elseif ($user === 'Don' || $user === 'don') {
            $port = '8015';
        }

        $curl = curl_init(sprintf('http://localhost:%s/customers/init',$port));
        curl_exec($curl);
        curl_close($curl);
    }



}
