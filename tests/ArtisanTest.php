<?php

namespace Tests\CoRex\Console;

use CoRex\Console\Artisan;
use CoRex\Console\ConsoleException;
use CoRex\Console\Path;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;
use Tests\CoRex\Console\Laravel\Commands\LaravelTestCommand;
use Tests\CoRex\Console\Symfony\Commands\SymfonyTestCommand;

class ArtisanTest extends TestCase
{
    private $check;

    /**
     * Test constructor.
     */
    public function testConstructor()
    {
        $artisan = new Artisan();
        $this->assertEquals([], Obj::getProperty('commands', $artisan));
        $this->assertEquals(true, Obj::getProperty('showInternalCommands', $artisan));
    }

    /**
     * Test setApplication not object.
     *
     * @throws ConsoleException
     */
    public function testSetApplicationNotObject()
    {
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage('Specified application is not an object.');
        $artisan = new Artisan();
        $artisan->setApplication(null);
    }

    /**
     * Test setApplication object.
     *
     * @throws ConsoleException
     */
    public function testSetApplicationObject()
    {
        $fakeApplication = new \stdClass();
        $fakeApplication->check = $this->check;
        $artisan = new Artisan();
        $artisan->setApplication($fakeApplication);
        $this->assertEquals($fakeApplication, Obj::getProperty('application', $artisan));
    }

    /**
     * Test setName.
     */
    public function testSetName()
    {
        $artisan = new Artisan();
        $this->assertNull(Obj::getProperty('name', $artisan));
        $artisan->setName($this->check);
        $this->assertEquals($this->check, Obj::getProperty('name', $artisan));
    }

    /**
     * Test setVersion.
     */
    public function testSetVersion()
    {
        $artisan = new Artisan();
        $this->assertNull(Obj::getProperty('version', $artisan));
        $artisan->setVersion($this->check);
        $this->assertEquals($this->check, Obj::getProperty('version', $artisan));
    }

    /**
     * Test hideInternalCommands.
     */
    public function testHideInternalCommands()
    {
        $artisan = new Artisan();
        $this->assertTrue(Obj::getProperty('showInternalCommands', $artisan));
        $artisan->hideInternalCommands();
        $this->assertFalse(Obj::getProperty('showInternalCommands', $artisan));
    }

    /**
     * Test addCommand.
     *
     * @throws \Exception
     */
    public function testAddCommand()
    {
        $artisan = new Artisan();
        $this->assertEquals([], Obj::getProperty('commands', $artisan));

        $artisan->addCommand(LaravelTestCommand::class);
        $artisan->addCommand(SymfonyTestCommand::class);

        $commands = Obj::getProperty('commands', $artisan);
        $this->assertTrue(array_key_exists(LaravelTestCommand::class, $commands));
        $this->assertTrue(array_key_exists(SymfonyTestCommand::class, $commands));
    }

    /**
     * Test addCommandsOnPath.
     *
     * @throws \Exception
     */
    public function testAddCommandsOnPath()
    {
        $artisan = new Artisan();
        $this->assertEquals([], Obj::getProperty('commands', $artisan));

        $artisan->addCommandsOnPath(__DIR__);

        $commands = Obj::getProperty('commands', $artisan);
        $this->assertTrue(array_key_exists(LaravelTestCommand::class, $commands));
        $this->assertTrue(array_key_exists(SymfonyTestCommand::class, $commands));
    }

    /**
     * Test addCommandsOnPackage.
     *
     * @throws \Exception
     */
    public function testAddCommandsOnPackage()
    {
        $artisan = new Artisan();
        $this->assertEquals([], Obj::getProperty('commands', $artisan));

        $artisan->addCommandsOnPackage(Path::vendorName(), Path::packageName());

        $commands = Obj::getProperty('commands', $artisan);
        $this->assertTrue(array_key_exists(LaravelTestCommand::class, $commands));
        $this->assertTrue(array_key_exists(SymfonyTestCommand::class, $commands));
    }

    /**
     * Test execute laravel test command.
     *
     * @throws \Exception
     */
    public function testExecuteLaravelTestCommand()
    {
        $artisan = new Artisan();
        $artisan->addCommandsOnPath(__DIR__);

        ob_start();
        $artisan->execute('laravel:test');
        $content = ob_get_clean();

        $this->assertEquals(LaravelTestCommand::class . '::handle' . "\n", $content);
    }

    /**
     * Test execute symfony test command.
     *
     * @throws \Exception
     */
    public function testExecuteSymfonyTestCommand()
    {
        $artisan = new Artisan();
        $artisan->addCommandsOnPath(__DIR__);

        ob_start();
        $artisan->execute('symfony:test');
        $content = ob_get_clean();

        $this->assertEquals(SymfonyTestCommand::class . '::execute' . "\n", $content);
    }

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->check = md5(mt_rand(1, 100000));
    }
}
