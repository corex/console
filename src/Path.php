<?php

namespace CoRex\Console;

class Path
{
    /**
     * Get path to root of site.
     *
     * @param array $segments Default [].
     * @return string
     */
    public static function root(array $segments = [])
    {
        $path = __DIR__;
        for ($c1 = 0; $c1 < 4; $c1++) {
            $path = dirname($path);
        }
        $path = str_replace('\\', '/', $path);
        if (count($segments) > 0) {
            $path .= '/' . implode('/', $segments);
        }
        return $path;
    }

    /**
     * Get path to current package.
     *
     * @param array $segments Default [].
     * @return string
     */
    public static function packageCurrent(array $segments = [])
    {
        return self::package(null, null, $segments);
    }

    /**
     * Get path to package.
     * Note: if both $vendor and $package is null, current package is returned.
     *
     * @param string $vendor Default null which means current.
     * @param string $package Default null which means current.
     * @param array $segments Default [].
     * @return string
     */
    public static function package($vendor = null, $package = null, array $segments = [])
    {
        $path = dirname(dirname(static::packagePath()));
        if ($package === null) {
            $package = static::packageName();
        }
        if ($vendor === null) {
            $vendor = static::vendorName();
        }
        $path .= '/' . $vendor . '/' . $package;
        if (count($segments) > 0) {
            $path .= '/' . implode('/', $segments);
        }
        return $path;
    }

    /**
     * Get vendor name.
     *
     * @return string
     */
    public static function vendorName()
    {
        $path = static::packagePath();
        return basename(dirname($path));
    }

    /**
     * Get package name.
     *
     * @return string
     */
    public static function packageName()
    {
        $path = static::packagePath();
        return basename($path);
    }

    /**
     * Get package path.
     *
     * @return string
     */
    protected static function packagePath()
    {
        return dirname(__DIR__);
    }
}
