<?php

namespace {namespace};

use CoRex\Console\BaseCommand;

class {Class} extends BaseCommand
{
    protected $signature = 'component:command';
    protected $description = 'Description of command';
    protected $hidden = false;

    /**
     * Run command.
     */
    public function handle()
    {
        $this->header($this->description);
    }
}