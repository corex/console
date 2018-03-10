<?php

namespace CoRex\Console\Commands;

use CoRex\Console\BaseCommand;
use CoRex\Console\Path;

class MakeArtisanCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:artisan
        {name : Name of artisan file without extension}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make artisan in project root';

    /**
     * Handle.
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('CoRex Console - ' . $this->description);
        $name = $this->argument('name');
        if (strpos($name, '.') !== false) {
            $this->throwError('It is not allowed to specify extension.');
        }

        $filename = Path::root([$name]);
        if (file_exists($filename)) {
            $this->throwError('Artisan ' . $name . ' already exists.');
        }

        try {
            copy(dirname(dirname(__DIR__)) . '/artisan', $filename);
        } catch (\Exception $e) {
            $this->throwError($e->getMessage());
        }

        // Show explanation.
        $this->line('');
        $this->info('Artisan ' . $name . ' (' . $filename . ') created.');
        $this->line('');
        $this->line('Modify ' . $filename . ' to suit your needs.');
    }
}
