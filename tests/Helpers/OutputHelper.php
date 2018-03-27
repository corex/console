<?php

namespace Tests\CoRex\Console\Helpers;

class OutputHelper
{
    private $content;

    /**
     * OutputHelper constructor.
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * Clear.
     */
    public function clear()
    {
        $this->content = '';
    }

    /**
     * Content.
     *
     * @return mixed
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Write.
     *
     * @param array|string $messages
     * @param boolean $newline
     */
    public function write($messages, $newline = false)
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }
        $this->addLines($messages, $newline);
    }

    /**
     * Writeln.
     *
     * @param array|string $messages
     */
    public function writeln($messages)
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }
        $this->addLines($messages, true);
    }

    /**
     * Add lines.
     *
     * @param array $messages
     * @param boolean $newline
     */
    public function addLines(array $messages, $newline)
    {
        $newline = $newline ? "\n" : '';
        foreach ($messages as $message) {
            $this->content .= $message . $newline;
        }
    }
}