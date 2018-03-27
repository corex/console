# CoRex Console
Console framework based on illuminate/console (artisan, commands, visibility).

**_Versioning for this package follows http://semver.org/. Backwards compatibility might break on upgrade to major versions._**

Laravel has a package called illuminate/console which makes an excellent job of serving commands.

Package corex/console makes it possible to have commands outside Laravel + a little more.

- Support for Commands using Laravel's implementation of commands.
- Support for Commands using Symfony's implementation of commands.


## Installation
- Run "composer require corex/console".


## Commands (internal).
- make:artisan - This command creates a new "artisan" in project root (created artisan can be modified to suit your needs).
- make:command - This command creates a new command in current directory.

A note on "artisan" file.
- It is possible to specify array of parameters or signature, on "$artisan->execute()" so it will execute command instead of showing list of commands.
- It is possible to override the property $hidden on commands on adding indidual command or scan for commands.


## Command
Go to Laravel's documentation to read how to write commands.

Every command created must end in "Command.php" i.e. "MyCommand.php". Otherwise it will not be added to list of available commands. It is possible to change that in Artisan setup.

When using "make:command" the created command will extend CoRex\Console\BaseCommand which extends Illuminate\Console\Command.

Following methods exists on BaseCommand.
- write() - Outputs text to console with no linebreak.
- writeln() - Outputs text to console with linebreak.
- header() - Outputs a header followed by a separator.
- separator() - Outputs a separator.
- words() - Outputs array of words separated (implode()).
- properties() - Outputs associative array key/value line by line.
- setLineLength() - This sets length of line i.e. used in separators.
- setLineLengthFull() - This sets length of line to i.e. used in separators to length of terminal.
- getLineLength() - Gets the length of i.e. separators.
- throwError() - Throw styled exception (white on red).
