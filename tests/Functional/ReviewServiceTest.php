<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use PHPUnit\Framework\TestCase;
use Review\Service\FileService;
use Review\Service\ReviewService;

/**
 * Class InvestmentTest
 */
class ReviewServiceTest extends TestCase
{

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var ReviewService
     */
    private $reviewService;

    /**
     * @var string
     */
    private $inputFile;

    /**
     * @var string
     */
    private $inputNotExistedFile;


    /**
     * @var string
     */
    private $apiBinServiceDefaultUrl;

    /**
     * @var
     */
    private $apiRatesServiceDefaultUrl;

    /**
     * Constructor in every test.
     */
    protected function setUp(): void
    {
        $this->inputFile           = '/var/code/input.txt';
        $this->inputNotExistedFile = '/var/code/input2.txt';
        $this->fileService         = new FileService();
        $this->reviewService       = new ReviewService();

        $this->apiBinServiceDefaultUrl   = ReviewService::DEFAULT_API_BIN_SERVICE;
        $this->apiRatesServiceDefaultUrl = ReviewService::DEFAULT_API_RATES_SERVICE;
    }

    /**
     * @test
     */
    public function testServices(): void
    {
        $stubFileService = $this->createMock(FileService::class);
        $stubService     = $this->createMock(ReviewService::class);

        $stubFileService
            ->expects(self::once())
            ->method('checkFileExist')
            ->willReturn(true);
        $fileExist = $stubFileService->checkFileExist($this->inputFile);

        $this->assertFileExists($this->inputFile);
        $this->assertEquals($fileExist, true);

        $stubFileService
            ->expects(self::once())
            ->method('setInputFile')
            ->willReturn($this->fileService);
        $result = $stubFileService->setInputFile($this->inputFile);
        $this->assertEquals($this->fileService, $result);

        $testUrl = 'https://lookup.binlist.net/';
        $stubService
            ->expects(self::once())
            ->method('setApiBinServiceUrl')
            ->willReturn($this->reviewService);

        $result = $stubService->setApiBinServiceUrl($testUrl);
        $this->assertEquals($this->reviewService, $result);

        $testUrl = 'https://api.exchangeratesapi.io/latest';
        $stubService
            ->expects(self::once())
            ->method('setApiRatesServiceUrl')
            ->willReturn($this->reviewService);

        $result = $stubService->setApiRatesServiceUrl($testUrl);
        $this->assertEquals($this->reviewService, $result);

        $rowData = json_decode('{"bin":"45717360","amount":"100.00","currency":"EUR"}', true);
        $stubService
            ->expects(self::once())
            ->method('isArrayRowData')
            ->willReturn(true);

        $stubService
            ->expects(self::once())
            ->method('validate')
            ->willReturn(true);

        $isArray  = $stubService->isArrayRowData($rowData);
        $validate = $stubService->validate($rowData);
        $this->assertEquals($isArray, true);
        $this->assertEquals($validate, true);


    }
}
