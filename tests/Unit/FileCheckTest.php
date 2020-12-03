<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use PHPUnit\Framework\TestCase;
use Review\Service\FileService;

class FileCheckTest extends TestCase
{
    /**
     * @var FileService
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

    /**
     * Constructor in every test.
     */
    protected function setUp(): void
    {
        $this->inputFile           = '/var/code/input.txt';
        $this->inputNotExistedFile = '/var/code/input2.txt';
        $this->service             = new FileService();
    }

    /**
     * @test
     */
    public function testInputFileExist(): void
    {
        $stub = $this->createMock(FileService::class);
        $stub->expects(self::once())
            ->method('checkFileExist')
            ->with($this->inputFile)
            ->willReturn(true);

        $fileExist = $stub->checkFileExist($this->inputFile );
        $this->assertFileExists($this->inputFile);
        $this->assertEquals($fileExist, true);
    }

    /**
     * @test
     */
    public function testInputFileDoesNotExist(): void
    {
        $this->service->setInputFile($this->inputNotExistedFile);
        $fileDoesNotExist = $this->service->checkFileExist($this->inputNotExistedFile);
        $this->assertFileDoesNotExist($this->service->getInputFile());
        $this->assertEquals($fileDoesNotExist, false);
    }

    /**
     * @test
     */
    public function testFileIsReadable(): void
    {
        $stub = $this->createMock(FileService::class);
        $stub->expects(self::once())
            ->method('checkFileIsReadable')
            ->with($this->inputFile)
            ->willReturn(true);

        $readable = $stub->checkFileIsReadable($this->inputFile);

        $this->assertFileIsReadable($this->inputFile);
        $this->assertEquals($readable, true);
    }

    /**
     * @test
     */
    public function testGetFileContent(): void
    {
        $expectedText =
'{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}';

        $stub = $this->createMock(FileService::class);
        $stub->expects(self::once())
            ->method('getFileContent')
            ->with($this->inputFile)
            ->willReturn($expectedText);

        $content = $stub->getFileContent($this->inputFile);

        $this->assertEquals($content, $expectedText);
    }
}
