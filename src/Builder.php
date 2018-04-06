<?php

namespace CoRex\Console;

use CoRex\Support\System\File;

class Builder
{
    private $templatePath;
    private $templateName;
    private $tokens;

    /**
     * Builder constructor.
     *
     * @param string $templateName
     */
    public function __construct($templateName)
    {
        $this->templatePath = rtrim(Path::packageCurrent('templates'), '/');
        $this->templateName = $templateName;
        $this->tokens = [];
    }

    /**
     * Template.
     *
     * @param string $templateName
     * @return static
     */
    public static function template($templateName)
    {
        return new static($templateName);
    }

    /**
     * Path.
     *
     * @param string $path
     * @return $this
     */
    public function path($path)
    {
        $this->templatePath = rtrim($path, '/');
        return $this;
    }

    /**
     * Token.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function token($name, $value)
    {
        $this->tokens[$name] = $value;
        return $this;
    }

    /**
     * Tokens.
     *
     * @param array $tokens
     * @return $this
     */
    public function tokens(array $tokens)
    {
        foreach ($tokens as $name => $value) {
            $this->token($name, $value);
        }
        return $this;
    }

    /**
     * Render.
     *
     * @return string
     */
    public function render()
    {
        $this->prepareTokenValues();
        return File::getTemplate(
            $this->templatePath . '/' . $this->templateName,
            $this->tokens
        );
    }

    /**
     * Save.
     *
     * @param string $filename
     */
    public function save($filename)
    {
        File::put($filename, $this->render());
    }

    /**
     * Prepare token values.
     */
    private function prepareTokenValues()
    {
        foreach ($this->tokens as $name => $value) {

            // Convert boolean to string representation.
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            $this->tokens[$name] = $value;
        }
    }
}