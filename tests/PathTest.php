<?php

namespace Tests\CoRex\Console;

use CoRex\Console\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /**
     * Test root.
     */
    public function testRoot()
    {
        $this->assertEquals(dirname(__DIR__), Path::root());
    }
}