#!/usr/bin/env php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
try {
    $artisan = new \CoRex\Console\Artisan();
    // Set name on artisan.
    //$artisan->setName('name');

    // Set version on artisan.
    //$artisan->setVersion('x.y.z');

    // Add single command.
    //$artisan->addCommand(MyCommand::class);

    // Add multiple commands on specified path.
    //$artisan->addCommandsOnPath(__DIR__ . '/Commands');

    // Execute artisan. It is possible to execute command directly by specifying it on execute().
    $artisan->execute();
} catch (Exception $e) {
    print($e->getMessage() . "\n");
}
