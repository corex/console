<?php

namespace CoRex\Console;

class Composer
{
    private static $filename;

    /**
     * Load.
     *
     * @param boolean $doLoad
     * @return boolean
     */
    public static function load($doLoad = true)
    {
        $path = self::filename();
        if ($path === null || !self::found()) {
            return false;
        }
        if ($doLoad) {
            require_once($path);
        }
        return true;
    }

    /**
     * Found.
     *
     * @return boolean
     */
    public static function found()
    {
        return file_exists(self::filename());
    }

    /**
     * Get filename.
     *
     * @return null|string
     */
    public static function filename()
    {
        if (self::$filename !== null) {
            return self::$filename;
        }
        $path = rtrim(__DIR__, '/');
        $isPath = self::isPath($path);
        while ($isPath === null) {
            $pos = strrpos($path, '/');
            $path = rtrim(substr($path, 0, $pos), '/');
            $isPath = self::isPath($path);
            if ($isPath !== null) {
                return $isPath;
            }
        }
        return null;
    }

    /**
     * Override filename.
     *
     * @param string $filename
     */
    public static function overrideFilename($filename)
    {
        self::$filename = $filename;
    }

    /**
     * Is path.
     *
     * @param string $path
     * @return string
     */
    private static function isPath($path)
    {
        if (file_exists($path . '/autoload.php')) {
            return $path . '/autoload.php';
        } elseif (file_exists($path . '/vendor/autoload.php')) {
            return $path . '/vendor/autoload.php';
        }
        return null;
    }
}