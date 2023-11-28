<?php
declare(strict_types=1);

namespace App\Command;

use App\Test\Scenario\IntegrationDataScenario;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class InitDBCommand extends Command
{
    use ScenarioAwareTrait;

    /**
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return int|null|void
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->loadFixtureScenario(IntegrationDataScenario::class);
        parent::execute($args, $io);

        return static::CODE_SUCCESS;
    }
}
