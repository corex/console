<?php

namespace Tests\CoRex\Console;

use CoRex\Console\Composer;
use CoRex\Support\System\File;
use PHPUnit\Framework\TestCase;

class ComposerTest extends TestCase
{
    /**
     * Test load.
     */
    public function testLoad()
    {
        $tempFilename = File::getTempFilename();
        Composer::overrideFilename($tempFilename);
        $this->assertTrue(Composer::load(false));
        File::delete($tempFilename);
        $this->assertFalse(Composer::load(false));
    }

    /**
     * Test found.
     */
    public function testFound()
    {
        $tempFilename = File::getTempFilename();
        Composer::overrideFilename($tempFilename);
        $this->assertTrue(Composer::found());
        File::delete($tempFilename);
        $this->assertFalse(Composer::found());
    }

    /**
     * Test filename.
     */
    public function testGetFilename()
    {
        $check = md5(mt_rand(1, 100000));
        Composer::overrideFilename($check);
        $this->assertEquals($check, Composer::filename());
    }

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        Composer::overrideFilename(null);
    }
}
