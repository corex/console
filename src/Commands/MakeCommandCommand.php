<?php

namespace CoRex\Console\Commands;

use CoRex\Console\BaseCommand;
use CoRex\Console\Path;

class MakeCommandCommand extends BaseCommand
{
    protected $signature = 'make:command
        {namespace : Namespace of command (/ will be converted to \)}
        {class : Name of class ("Command" will be added to filename}';
    protected $description = 'Make command in current directory';

    /**
     * Run command.
     * @throws \Exception
     */
    public function handle()
    {
        $namespace = $this->argument('namespace');
        $namespace = str_replace('/', '\\', $namespace);
        $class = ucfirst($this->argument('class'));

        // Make sure class ends with "Command".
        if (substr($class, -7) != 'Command') {
            $class .= 'Command';
        }

        // Write template.
        $commandFilename = $class . '.php';
        if (file_exists($commandFilename)) {
            $this->throwError('Command filename ' . $commandFilename . ' already exists.');
        }
        $templateFilename = Path::packageCurrent(['templates', 'command.tpl']);
        $template = file_get_contents($templateFilename);
        $template = str_replace('{namespace}', $namespace, $template);
        $template = str_replace('{Class}', $class, $template);
        file_put_contents($commandFilename, $template);

        $this->info($commandFilename . ' created.');
    }
}