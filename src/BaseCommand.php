<?php

namespace CoRex\Console;

use Illuminate\Console\Command as IlluminateCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Terminal;

abstract class BaseCommand extends IlluminateCommand
{
    private $terminal;
    private $lineLength = 80;

    /**
     * Set length of line.
     *
     * @param integer $lineLength
     */
    public function setLineLength($lineLength)
    {
        $this->lineLength = $lineLength;
    }

    /**
     * Set full length of line.
     */
    public function setLineLengthFull()
    {
        $this->setLineLength($this->getTerminalWidth());
    }

    /**
     * Get length of line.
     *
     * @return integer
     */
    public function getLineLength()
    {
        return $this->lineLength;
    }

    /**
     * Write messages.
     *
     * @param string|array $messages
     * @param boolean $newline Default false.
     * @throws \Exception
     */
    public function write($messages, $newline = false)
    {
        $this->output->write($messages, $newline);
    }

    /**
     * Write messages with linebreak.
     *
     * @param string|array $messages
     */
    public function writeln($messages)
    {
        $this->output->writeln($messages);
    }

    /**
     * Write header (title + separator).
     *
     * @param string $title
     */
    public function header($title)
    {
        $title = str_pad($title, $this->getLineLength(), ' ', STR_PAD_RIGHT);
        $this->writeln($title);
        $this->separator('=');
    }

    /**
     * Write separator-line.
     *
     * @param string $character Default '='.
     */
    public function separator($character = '=')
    {
        $this->writeln(str_repeat($character, $this->getLineLength()));
    }

    /**
     * Write words.
     *
     * @param array $words
     * @param string $separator Default ', '.
     * @throws \Exception
     */
    public function words(array $words, $separator = ', ')
    {
        $this->write(implode($separator, $words));
    }

    /**
     * Throw error-message as exception.
     *
     * @param string $message
     * @throws ConsoleException
     */
    public function throwError($message)
    {
        throw new ConsoleException($this->applyStyle($message, 'white', 'red'));
    }

    /**
     * Properties.
     *
     * @param array $data
     * @param string $separator Default ':'.
     * @param string $prefix Default null.
     * @throws \Exception
     */
    public function properties(array $data, $separator = ':', $prefix = null)
    {
        $keys = array_keys($data);
        $maxLength = max(array_map('strlen', $keys));
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $key = str_pad($key, $maxLength, ' ', STR_PAD_RIGHT);
                if ($prefix !== null) {
                    $this->write($prefix);
                }
                $this->write($key);
                $this->write(' ');
                if (Str::length($separator) > 0) {
                    $this->write($separator . ' ');
                }
                $this->writeln($value);
            }
        }
    }

    /**
     * Get terminal height.
     *
     * @return integer
     */
    public function getTerminalHeight()
    {
        return $this->getTerminal()->getHeight();
    }

    /**
     * Get terminal width.
     *
     * @return integer
     */
    public function getTerminalWidth()
    {
        return $this->getTerminal()->getWidth();
    }

    /**
     * Get terminal.
     *
     * @return Terminal
     */
    private function getTerminal()
    {
        if (!is_object($this->terminal)) {
            $this->terminal = new Terminal();
        }
        return $this->terminal;
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
}