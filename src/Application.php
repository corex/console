<?php

namespace CoRex\Console;

use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Application extends ConsoleApplication
{
    private $commands;

    /**
     * ConsoleApplication constructor.
     *
     * @param Container $laravel
     * @param Dispatcher $events
     * @param string $version
     * @param array $commands
     */
    public function __construct(Container $laravel, Dispatcher $events, $version, array $commands)
    {
        $this->commands = $commands;
        parent::__construct($laravel, $events, $version);
    }

    /**
     * Add command.
     *
     * @param SymfonyCommand $command
     * @return SymfonyCommand
     */
    public function add(SymfonyCommand $command)
    {
        if (!in_array(get_class($command), $this->commands)) {
            $command->setHidden(true);
        }
        return parent::add($command);
    }
}