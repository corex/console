<?php

namespace Tests\CoRex\Console\Symfony\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyTestCommand extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        $this->setName('symfony:test');
        $this->setDescription('Symfony Test Command');
    }

    /**
     * @param InputInterface $input The input instance
     * @param OutputInterface $output The output instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        print(__CLASS__ . '::' . __FUNCTION__ . "\n");
    }
}
