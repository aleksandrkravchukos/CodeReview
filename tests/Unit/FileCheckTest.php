<?php


namespace ReviewTest\Unit;

use PHPUnit\Framework\TestCase;
use Review\Service\ReviewService;

class FileCheckTest extends TestCase
{
    /**
     * @var ReviewService
     */
    private $service;

    /**
     * @var string
     */
    private $inputFile;

    /**
     * @var string
     */
    private $inputNotExistedFile;

    protected function setUp(): void
    {
        $this->inputFile = '/var/code/input.txt';
        $this->inputNotExistedFile = '/var/code/input2.txt';
        $this->service = new ReviewService();
    }

    /**
     * @test
     */
    public function testInputFileExist()
    {
        $this->service->setInputFile($this->inputFile);
        $fileExist = $this->service->checkFileExist();
        $this->assertFileExists($this->service->getInputFile());
        $this->assertEquals($fileExist, true);
    }

    /**
     * @test
     */
    public function testInputFileDoesNotExist()
    {
        $this->service->setInputFile($this->inputNotExistedFile);
        $fileDoesNotExist = $this->service->checkFileExist();
        $this->assertFileDoesNotExist($this->service->getInputFile());
        $this->assertEquals($fileDoesNotExist, false);
    }

    /**
     * @test
     */
    public function testFileIsReadable()
    {
        $this->service->setInputFile($this->inputFile);
        $readable = $this->service->checkFileIsReadable();

        $this->assertFileIsReadable($this->service->getInputFile());
        $this->assertEquals($readable, true);
    }

    /**
     * @test
     */
    public function testGetFileContent()
    {
        $expectedText =
'{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}';

        $this->service->setInputFile($this->inputFile);
        $content = $this->service->getFileContent();

        $this->assertEquals($content, $expectedText);
    }
}