<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class EditYamlCommand extends Command
{
    private $filenames = [
        'docker-compose_src.yaml',
        'phinx_src.php',
        'bin/db_setup_src.sh',
    ];
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->addArgument('spn', [
                'help' => 'Short project name',
            ])
            ->addOption('dbport', [
                'short' => 'd',
                'help' => 'Database port',
                'default' => '9010',
            ])
            ->addOption('webport', [
                'short' => 'w',
                'help' => 'Web port',
                'default' => '8010',
            ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
//        $spn = $args->getArgument('spn');
        $a_short_name = $io->ask('Short project name (try to keep under 5 letters):');
        $a_dbport = $io->ask('Database port (default is 3011):');
        $a_webport = $io->ask('Web port (default is 80, we typically increment from 8010):');
        $io->out("Short Name: $a_short_name");
        $io->out("DB Port: $a_dbport");
        $io->out("Webport: $a_webport");

        $a_db_user = $io->ask('DB Username (not root):');
        $a_db_password = $this->matchStrings("DB $a_db_user password", $io);
        $a_db_root_password = $this->matchStrings("DB root password" , $io);

        $io->out($a_db_user);
        $io->out($a_db_password);
        $io->out($a_db_root_password);
//        if (!$spn) {
//            $io->out('Must provide short project name');
//
//            return static::CODE_ERROR;
//        }
//
//        $dbport = $args->getOption('dbport');
//        $webport = $args->getOption('webport');
//        $dbservice = $spn . '_mysql';
//        $webservice = $spn . '_cakephp';
//
//        foreach ($this->filenames as $filename) {
//            $file = file_get_contents(ROOT . '/' . $filename);
//            $dest_filename = str_replace('_src', '', $filename);
//
//            if (!$file) {
//                $io->out("$filename not found");
//            } else {
//                $file = str_replace('[[db_port]]', $dbport, $file);
//                $file = str_replace('[[web_port]]', $webport, $file);
//                $file = str_replace('[[db_service]]', $dbservice, $file);
//                $file = str_replace('[[web_service]]', $webservice, $file);
//                $file = str_replace('[[short_name]]', $spn, $file);
//                file_put_contents($dest_filename, $file);
//                $io->out("$dest_filename amended");
//            }
//        }

        return static::CODE_SUCCESS;
    }

    //edit phinx.php
    //edit bin/db_setup.sh
    //add exec method to execute BASH commands

    private function replaceText($file, $search, $replace, $io)
    {
        if (!$file) {
            $io->out('No file found');

            return static::CODE_ERROR;
        } else {
            return str_replace($search, $replace, $file);
        }
    }

    private function matchStrings($prompt, ConsoleIo $io)
    {
        $input = 1;
        $match = 2;
        $match_prompt = '';

        while ($input !== $match) {
            $match_prompt == '' ? : $io->out($match_prompt);
            $input = $io->ask("$prompt:");
            $match = $io->ask("Retype:");
            $match_prompt = 'Strings must match';
        }
        return $input;
    }
}
