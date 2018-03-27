<?php

namespace Tests\CoRex\Console;

use CoRex\Console\BaseCommand;
use CoRex\Console\ConsoleException;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;
use Tests\CoRex\Console\Helpers\BaseCommandHelper;
use Tests\CoRex\Console\Helpers\OutputHelper;

class BaseCommandTest extends TestCase
{
    /**
     * @var BaseCommand
     */
    private $baseCommand;

    /**
     * @var OutputHelper
     */
    private $output;

    private $check;

    /**
     * Test get line length default.
     */
    public function testGetLineLengthDefault()
    {
        $this->assertEquals(80, $this->baseCommand->getLineLength());
    }

    /**
     * Test set/get line length.
     */
    public function testSetGetLineLength()
    {
        $lineLength = mt_rand(1, 200);
        $this->baseCommand->setLineLength($lineLength);
        $this->assertEquals($lineLength, $this->baseCommand->getLineLength());
    }

    /**
     * Test set line length full.
     */
    public function testSetLineLengthFull()
    {
        $this->baseCommand->setLineLengthFull();
        $this->assertEquals($this->baseCommand->getTerminalWidth(), $this->baseCommand->getLineLength());
    }

    /**
     * Test write.
     *
     * @throws \Exception
     */
    public function testWrite()
    {
        $this->baseCommand->write($this->check, true);
        $this->baseCommand->write($this->check);
        $this->assertEquals($this->check . "\n" . $this->check, $this->output->content());
    }

    /**
     * Test writeln.
     */
    public function testWriteln()
    {
        $this->baseCommand->writeln($this->check);
        $this->baseCommand->writeln([$this->check, $this->check]);
        $this->assertEquals($this->check . "\n" . $this->check . "\n" . $this->check . "\n", $this->output->content());
    }

    /**
     * Test header.
     */
    public function testHeader()
    {
        $this->baseCommand->header($this->check);
        $checkHeader = str_pad($this->check, $this->baseCommand->getLineLength(), ' ', STR_PAD_RIGHT) . "\n";
        $checkHeader .= str_repeat('=', $this->baseCommand->getLineLength()) . "\n";
        $this->assertEquals($checkHeader, $this->output->content());
    }

    /**
     * Test separator.
     */
    public function testSeparator()
    {
        $this->baseCommand->separator();
        $this->assertEquals(
            str_repeat('=', $this->baseCommand->getLineLength()) . "\n",
            $this->output->content()
        );
    }

    /**
     * Test words.
     *
     * @throws \Exception
     */
    public function testWords()
    {
        $words = ['test1', 'test2'];
        $this->baseCommand->words($words);
        $this->assertEquals(implode(', ', $words), $this->output->content());
    }

    /**
     * Test throw error.
     *
     * @throws ConsoleException
     */
    public function testThrowError()
    {
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage($this->check);
        $this->baseCommand->throwError($this->check);
    }

    /**
     * Test properties.
     *
     * @throws \Exception
     */
    public function testProperties()
    {
        $properties = [
            'test1' => '1test',
            'test2' => '2test'
        ];
        $this->baseCommand->properties($properties, '=', '-');
        $checkProperties = [];
        foreach ($properties as $key => $value) {
            $checkProperties[] = '-' . $key . ' = ' . $value . "\n";
        }
        $this->assertEquals(implode('', $checkProperties), $this->output->content());
    }

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->baseCommand = new BaseCommandHelper();
        $this->output = new OutputHelper();
        $this->output->clear();
        Obj::setProperty('output', $this->baseCommand, $this->output);
        $this->check = md5(mt_rand(1, 100000));
    }
}
