<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Review\Service\ApiService;

class CheckApiTest extends TestCase
{
    /**
     * @var ApiService
     */
    private $service;

    /**
     * @var string
     */
    private $apiBinUrl;

    /**
     * @var string
     */
    private $apiRatesUrl;

    /**
     * Constructor in every test.
     */
    protected function setUp(): void
    {
        $this->apiBinUrl   = 'https://lookup.binlist.net/';
        $this->apiRatesUrl = 'https://api.exchangeratesapi.io/latest';
        $this->service     = new ApiService(new Client);
    }

    /**
     * @test
     */
    public function testApiBinService(): void
    {
        $apiBinData = $this->service->getApiServiceData($this->apiBinUrl);
        $this->assertEquals($apiBinData['success'], true);

        $apiBinData = $this->service->getApiServiceData($this->apiBinUrl . 'not_valid');
        $this->assertEquals($apiBinData['success'], false);
    }

    /**
     * @test
     */
    public function testApiRatesService(): void
    {
        $apiRatesData = $this->service->getApiServiceData($this->apiRatesUrl);
        $this->assertEquals($apiRatesData['success'], true);

        $apiRatesData = $this->service->getApiServiceData($this->apiRatesUrl . 'not_valid');
        $this->assertEquals($apiRatesData['success'], false);
    }
}
