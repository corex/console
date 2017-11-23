<?php

namespace CoRex\Console;

use Illuminate\Console\Application;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Artisan
{
    private $name;
    private $version;
    private $commands;
    private $showInternalCommands;

    /**
     * Artisan constructor.
     */
    public function __construct()
    {
        $this->commands = [];
        $this->showInternalCommands = true;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set version.
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Hide internal commands.
     *
     * @return $this
     */
    public function hideInternalCommands()
    {
        $this->showInternalCommands = false;
        return $this;
    }

    /**
     * Add command.
     *
     * @param string $command
     * @param boolean $hidden Default null which means command decide.
     * @return $this
     * @throws \Exception
     */
    public function addCommand($command, $hidden = null)
    {
        if (!is_string($command)) {
            throw new \Exception('You must specify class.');
        }
        $this->commands[$command] = $hidden;
        return $this;
    }

    /**
     * Add commands on path.
     *
     * @param string $path
     * @param boolean $hidden Default null which means command decide.
     * @param boolean $recursive Default true.
     * @param string $commandSuffix Default null.
     * @return $this
     */
    public function addCommandsOnPath($path, $hidden = null, $recursive = true, $commandSuffix = null)
    {
        if ($commandSuffix === null) {
            $commandSuffix = 'Command';
        }
        if (substr($commandSuffix, -4) != '.php') {
            $commandSuffix .= '.php';
        }
        $path = str_replace('\\', '/', $path);
        if (strlen($path) > 0 && substr($path, -1) == '/') {
            $path = rtrim($path, '//');
        }
        if (!is_dir($path)) {
            return $this;
        }
        $files = scandir($path);
        if (count($files) == 0) {
            return $this;
        }
        foreach ($files as $file) {
            if (substr($file, 0, 1) == '.') {
                continue;
            }
            if (substr($file, -strlen($commandSuffix)) == $commandSuffix) {
                $class = $this->extractFullClass($path . '/' . $file);
                if ($class != '') {
                    $this->addCommand($class, $hidden);
                }
            }
            if (is_dir($path . '/' . $file) && $recursive) {
                $this->addCommandsOnPath($path . '/' . $file, $hidden, $recursive, $commandSuffix);
            }
        }
        return $this;
    }

    /**
     * Add commands on package.
     *
     * @param string $vendor
     * @param string $package
     * @param string $additionalPath Default null.
     * @param boolean $hidden Default null which means command decide.
     * @param boolean $recursive Default true.
     * @param string $commandSuffix Default null.
     * @return $this
     * @throws \Exception
     */
    public function addCommandsOnPackage(
        $vendor,
        $package,
        $additionalPath = null,
        $hidden = null,
        $recursive = true,
        $commandSuffix = null
    ) {
        $segments = [];
        if ($additionalPath !== null) {
            $segments[] = $additionalPath;
        }
        $path = Path::package($vendor, $package, $segments);
        if (!is_dir($path)) {
            throw new \Exception('Path ' . $path . ' does not exist.');
        }
        $this->addCommandsOnPath($path, $hidden, $recursive, $commandSuffix);
        return $this;
    }

    /**
     * Execute console application.
     *
     * @param string $signature Default null which means all.
     * @return integer Exit code.
     */
    public function execute($signature = null)
    {
        // Set name and version if not set.
        if ($this->name === null) {
            $this->name = Constants::TITLE;
        }
        if ($this->version === null) {
            $this->version = Constants::VERSION;
        }

        // Add internal commands.
        if ($this->showInternalCommands) {
            $this->addCommandsOnPath(__DIR__ . '/Commands');
        }

        $exitCode = 0;
        try {

            // Setup.
            $container = Container::getInstance();
            $dispatcher = new Dispatcher($container);
            $app = new Application($container, $dispatcher, $this->version);
            $app->setName($this->name);

            // Add instance of commands.
            if (count($this->commands) > 0) {
                foreach ($this->commands as $command => $hidden) {
                    $commandObject = $this->newCommandObject($command);
                    if ($hidden !== null) {
                        $commandObject->setHidden($hidden);
                    }
                    $app->add($commandObject);
                }
            }

            // Prepare argv.
            $argv = $_SERVER['argv'];
            if ($signature !== null) {
                reset($argv);
                $firstElement = array_shift($argv);
                array_unshift($argv, $signature);
                array_unshift($argv, $firstElement);
            }

            // Execute.
            $exitCode = $app->run(
                new ArgvInput($argv),
                new ConsoleOutput()
            );
        } catch (\Exception $exception) {
            $message = $this->applyStyle($exception->getMessage(), 'white', 'red');
            print($message . "\n");
        }

        return intval($exitCode);
    }

    /**
     * Extract full class.
     *
     * @param string $filename
     * @return string
     */
    private function extractFullClass($filename)
    {
        $result = '';
        if (file_exists($filename)) {
            $data = $this->getFileContent($filename);
            $data = explode("\n", $data);
            if (count($data) > 0) {
                $namespace = '';
                $class = '';
                foreach ($data as $line) {
                    $line = str_replace('  ', ' ', $line);
                    if (substr($line, 0, 9) == 'namespace') {
                        $namespace = $this->getPart($line, 2, ' ');
                        $namespace = rtrim($namespace, ';');
                    }
                    if (substr($line, 0, 5) == 'class') {
                        $class = $this->getPart($line, 2, ' ');
                    }
                }
                if ($namespace != '' && $class != '') {
                    $result = $namespace . '\\' . $class;
                }
            }
        }
        return $result;
    }

    /**
     * Get part.
     *
     * @param string $data
     * @param integer $index
     * @param string $separator Trims data on $separator..
     * @return string
     */
    private function getPart($data, $index, $separator)
    {
        $data = trim($data, $separator) . $separator;
        if ($data != '') {
            $data = explode($separator, $data);
            if (isset($data[$index - 1])) {
                return $data[$index - 1];
            }
        }
        return '';
    }

    /**
     * Get file content.
     *
     * @param string $filename
     * @return string
     */
    private function getFileContent($filename)
    {
        $content = '';
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $content = str_replace("\r", '', $content);
        }
        return $content;
    }

    /**
     * Apply style.
     *
     * @param string $text
     * @param string $foreground Default ''.
     * @param string $background Default ''.
     * @return string
     * @see OutputFormatterStyle for foreground/background colors.
     */
    private function applyStyle($text, $foreground = '', $background = '')
    {
        $style = new OutputFormatterStyle();
        if ($foreground != '') {
            $style->setForeground($foreground);
        }
        if ($background != '') {
            $style->setBackground($background);
        }
        return $style->apply($text);
    }

    /**
     * New command object.
     *
     * @param string $commandClass
     * @return BaseCommand
     */
    private function newCommandObject($commandClass)
    {
        return new $commandClass();
    }
}