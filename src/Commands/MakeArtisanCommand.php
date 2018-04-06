<?php

namespace CoRex\Console\Commands;

use CoRex\Console\Artisan;
use CoRex\Console\BaseCommand;
use CoRex\Console\Builder;
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

        // Check if artisan already exist.
        if (file_exists($filename)) {
            $this->throwError('Artisan ' . $name . ' already exists.');
        }

        // Build and save.
        Builder::template('artisan')->tokens([
            'class' => Artisan::class,
            'hide' => true,
            'name' => 'CoRex Console',
            'version' => ''
        ])->save($filename);

        // Change to execute.
        chmod($filename, 0700);

        // Show explanation.
        $this->line('');
        $this->info('Artisan ' . $name . ' (' . $filename . ') created.');
        $this->line('');
        $this->line('Modify ' . $filename . ' to suit your needs.');
    }
}
