<?php

namespace Tests\CoRex\Console\Laravel\Commands;

use Illuminate\Console\Command;

class LaravelTestCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laravel:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravel Test Command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        print(__CLASS__ . '::' . __FUNCTION__ . "\n");
    }
}
