<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use mysql_xdevapi\Collection;

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

        $ok = 'No';
        $variables = [];
        while ($ok !== 'Yes') {
            $variables['SHORT_NAME'] = $io->ask('Short project name (try to keep under 5 letters):', $variables['SHORT_NAME']??'');
            $variables['DB_PORT'] = $io->ask('Database port (default is 3011):', $variables['DB_PORT']??'');
            $variables['WEB_PORT'] = $io->ask('Web port (default is 80, we typically increment from 8010):', $variables['WEB_PORT']??'');
            $variables['DB_USERNAME'] = $io->ask('DB Username (not root):',$variables['DB_USERNAME']??'');
            $variables['DB_USER_PASS'] = $this->matchStrings("DB {$variables['DB_USERNAME']} password", $io);
            $variables['DB_ROOT_PASS'] = $this->matchStrings("DB root password", $io);
            foreach ($variables as $variable => $value) {
                $io->out("$variable: $value");
            }
            $ok = $io->askChoice("Values OK?", ['Yes', 'No'], 'Yes');
        }

        $this->writeEnvironmentFile($variables, $io);
        $this->writeConstantFile($variables, $io);
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

    private function writeEnvironmentFile(array $variables, ConsoleIo $io)
    {
        $out = '';
        foreach ($variables as $variable => $value) {
            $out .= "$variable: $value\n";
        }
        $io->createFile(ROOT . DS . '.env_test', $out, true);

    }

    private function writeConstantFile(array $variables, ConsoleIo $io)
    {
        $out = "<?php\n";
        foreach ($variables as $variable => $value) {
            $out .= "const $variable = '$value';\n";
        }
        $io->createFile(CONFIG . DS . 'conv.php', $out, true);

    }
}
