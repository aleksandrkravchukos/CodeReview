<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Review\Service\ApiService;
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
     * @var ApiService
     */
    private $apiService;

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
        $this->apiService          = new ApiService(new Client);

        $this->apiBinServiceDefaultUrl   = ApiService::DEFAULT_API_BIN_SERVICE;
        $this->apiRatesServiceDefaultUrl = ApiService::DEFAULT_API_RATES_SERVICE;
    }

    /**
     * @test
     */
    public function testServices(): void
    {
        $fileExist = $this->fileService->checkFileExist($this->inputFile);

        $this->assertFileExists($this->inputFile);
        $this->assertEquals($fileExist, true);

        $result = $this->fileService->setInputFile($this->inputFile);
        $this->assertEquals($this->fileService, $result);

        $testUrl = 'https://lookup.binlist.net/';
        $result  = $this->apiService->setApiBinServiceUrl($testUrl);
        $this->assertEquals($this->apiService, $result);

        $testUrl = 'https://api.exchangeratesapi.io/latest';

        $result = $this->apiService->setApiRatesServiceUrl($testUrl);
        $this->assertEquals($this->apiService, $result);

        $rowData = json_decode('{"bin":"45717360","amount":"100.00","currency":"EUR"}', true);

        $isArray  = $this->reviewService->isArrayRowData($rowData);
        $validate = $this->reviewService->validate($rowData);
        $this->assertEquals($isArray, true);
        $this->assertEquals($validate, true);
    }
}
