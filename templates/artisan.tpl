#!/usr/bin/env php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
try {
    $artisan = new \{class}();
    $artisan->hideInternalCommands({hide});
    $artisan->setName('{name}');
    $artisan->setVersion('{version}');

    // Add single command.
    //$artisan->addCommand(MyCommand::class);

    // Add multiple commands on specified path.
    //$artisan->addCommandsOnPath(__DIR__ . '/Commands');

    // Execute artisan. It is possible to execute command directly by specifying it on execute().
    $artisan->execute();
} catch (Exception $e) {
    print($e->getMessage() . "\n");
}
